<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\invest\models;


use common\models\Config;
use wanhunet\base\Order;
use wanhunet\base\Product;
use wanhunet\db\ActiveRecord;
use wanhunet\helpers\ErrorCode;
use wanhunet\helpers\Utils;
use wanhunet\wanhunet;
use yii\base\ErrorException;

/**
 * Class Invest
 * @package modules\invest\models
 * @property integer $id
 * @property string $title 标题
 * @property string $introduce 介绍
 * @property integer $amount 标总数
 * @property integer $buy_time_start 开始购买时间
 * @property integer $buy_time_end 结束购买时间
 * @property integer $each_max 每人最多购买
 * @property integer $each_min 每人最少购买
 * @property integer $smarty_each_min 每人最少购买，计算剩余量
 * @property integer $rate 利率
 * @property integer $invest_date 还息时间几月标
 * @property integer $type 标类型
 * @property integer $created_at 创建时间
 * @property integer $updated_at 更新时间
 *
 * @property InvestList[] $investLists
 *
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class Invest extends ActiveRecord implements Product
{
    const EVENT_AFTER_SAVE_ORDER = 'afterSaveOrder';

    const TYPE_EXPERIENCE_MONEY = 10;
    const TYPE_MONEY = 20;
    const TYPE_REG = 30;

    public static function get_type($status)
    {
        switch ($status) {
            case self::TYPE_EXPERIENCE_MONEY:
                return '体验标';
                break;
            case self::TYPE_MONEY:
                return '真实标';
                break;
            case self::TYPE_REG:
                return '注册专属标';
                break;
            default:
                return $status;
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%invest}}';
    }

    public function behaviors()
    {
        return Utils::behaviorMerge(self::className(), parent::behaviors());
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'introduce', 'amount', 'buy_time_start', 'buy_time_end', 'each_max', 'each_min', 'rate', 'invest_date'], 'required'],
            [['amount', 'buy_time_start', 'buy_time_end', 'each_max', 'each_min', 'invest_date', 'created_at', 'updated_at'], 'integer'],
            [['title', 'introduce'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'introduce' => '介绍',
            'amount' => '标总数',
            'buy_time_start' => '开始购买时间',
            'buy_time_end' => '结束购买时间',
            'each_max' => '每人最多购买',
            'each_min' => '每人最少购买',
            'rate' => '利率',
            'invest_date' => '还息时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvestLists()
    {
        return $this->hasMany(InvestList::className(), ['invest_id' => 'id']);
    }

    public function getSmartyEachMin()
    {

    }

    /**
     * 通过用户请求生成订单
     * @param Order $order
     * @return InvestList
     * @throws ErrorException
     */
    public function markOrder(Order $order)
    {
        $price = $order->getPrice();
        $orderSum = InvestList::getAlreadyBuy($this);

        if (Config::getInstance()->getProperty('Invest.sumConfig') == 'both') {
            $sum = $orderSum[InvestList::STATUS_PAYED] + $orderSum[InvestList::STATUS_UNPAYED];
        } else {
            //TODO 可能出现问题由于这个是计算的已付的金额
            $sum = $orderSum[InvestList::STATUS_PAYED];
        }

        $rest = ($this->amount - $sum);
        $eachMax = ($this->each_max == 0 ? $this->amount : $this->each_max);
        $eachMin = $rest < $this->each_min ? $rest : $this->each_min;

        if ($price > $rest || $price > $eachMax) {
            throw new ErrorException('您购买的金额超出最大购买量', ErrorCode::Buy_each_max);
        }
        if ($price < $eachMin) {
            throw new ErrorException('您购买的金额小于最少购买量', ErrorCode::Buy_each_min);
        }

        if (time() > $this->buy_time_end || time() < $this->buy_time_start) {
            throw new ErrorException('您购买的不在购买时间内', ErrorCode::Buy_out_time);
        }

        if ($this->type == self::TYPE_EXPERIENCE_MONEY) {
            if ($price > wanhunet::app()->member->getExperienceMoneyMax()) {
                throw new ErrorException('您购买的金额超出您个人最大体验金购买量', ErrorCode::Buy_experience_money_max);
            }
        }
        /*if (InvestList::hasBuy(wanhunet::app()->member->getId(), $this->id)) {
            throw new ErrorException('您已经买过了', ErrorCode::Buy_has_buy);
        }*/

        //TODO 这个[[wanhunet::app()->member->getId()]] 应该动态配置

        $order->setUserId(wanhunet::app()->member->getId());
        $order->setProductId($this->id);
        $saveOrder = $order->saveOrder();
        if ($saveOrder) {
            $this->afterMarkOrder();
        }
        return $order;
    }

    public function markOrderReg(Order $order)
    {
        $price = $order->getPrice();

        $order->setUserId(wanhunet::app()->member->getId());
        $order->setProductId($this->id);
        $saveOrder = $order->saveOrder();
        if ($saveOrder) {
            $this->afterMarkOrder();
        }
        return $order;
    }

    protected function afterMarkOrder()
    {
        $this->trigger(self::EVENT_AFTER_SAVE_ORDER);
    }

    /**
     * @param null $type
     * @return array|null|Invest
     */
    public static function findByLately($type = null)
    {
        return self::find()
            ->where(['>=', 'buy_time_start', time()])
            ->andWhere(['type' => (is_null($type) ? self::TYPE_MONEY : $type)])
            ->orderBy('id asc')
            ->one();
    }

}