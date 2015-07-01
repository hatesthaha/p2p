<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\invest\models;


use common\models\Config;
use modules\member\models\Member;
use wanhunet\base\Order;
use wanhunet\db\ActiveRecord;
use wanhunet\helpers\Utils;
use wanhunet\wanhunet;
use yii\base\Event;

/**
 * Class InvestList
 * @package modules\invest\models
 * @property integer $id
 * @property integer $invest_id
 * @property integer $member_id
 * @property integer $investment_sum
 * @property integer $created_at
 * @property integer $pay_time
 * @property integer $updated_at
 * @property integer $status
 * @property integer $interest_status
 * @property integer $interest_time
 * @property integer $interest
 *
 * @property Invest $investModel
 * @property Invest $invest
 * @property Member $memberModel
 * @property Member $member
 *
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class InvestList extends ActiveRecord implements Order
{

    const EVENT_BEFORE_FINISH_PAY = 'beforeFinishPay';
    const EVENT_AFTER_FINISH_PAY = 'afterFinishPay';

    public $url = [];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%invest_list}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invest_id', 'member_id', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    public static function  get_status($status)
    {
        switch ($status) {
            case self::STATUS_ORDER_MADE:
                return '已建立';
                break;
            case self::STATUS_UNPAYED:
                return '未支付';
                break;
            case self::STATUS_PAYED:
                return '已支付';
                break;
            case self::STATUS_ORDER_FIRST_TRIAL:
                return '初审通过';
                break;
            case self::STATUS_ORDER_TRIAL:
                return '终审通过';
                break;
            default:
                return $status;
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invest_id' => 'Invest ID',
            'member_id' => 'Member ID',
            'investment_sum' => 'Investment Sum',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return Invest
     */
    public function getInvestModel()
    {
        return Invest::findOne($this->invest_id);
    }

    public function getInvest()
    {
        return $this->hasOne(Invest::className(), ['id' => 'invest_id']);
    }

    /**
     * @return Member
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return Utils::behaviorMerge(self::className(), parent::behaviors());
    }

    public function getEarnings($days = null)
    {
        if ($days !== null) {
            $dayNum = ($this->interest_time - $this->pay_time) / (3600 * 24);
            $dayEarn = $this->interest / $dayNum;
            if ($days == 'now') {
                $days = (time() - $this->pay_time) / (3600 * 24);
            }
            if ($days == 1) {
                if ((time() - $this->pay_time) / (3600 * 24) < 1) {
                    $days = (strtotime(date("Y-m-d")) - $this->pay_time) / (3600 * 24);
                    if ($days < 0) {
                        $days = 0;
                    }
                }
            }
            return $dayEarn * $days;
        }
        return $this->interest;
    }


    /**
     * 获取订单ID
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->id;
    }

    /**
     * 获取订单其他信息
     * @return mixed
     */
    public function getParams()
    {
        return [];
    }

    /**
     * 获取订单总额
     * @return mixed
     */
    public function getPrice()
    {
        return $this->investment_sum;
    }

    /**
     * 获取订单状态
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * 获取订单类型
     * @return mixed
     */
    public function getType()
    {
        return $this->getInvestModel()->type;
    }

    /**
     * 设置产品ID
     * @param int $id
     * @return mixed
     */
    public function setProductId($id)
    {
        $this->invest_id = $id;
    }

    /**
     * 设置用户ID
     * @param int $id
     * @return mixed
     */
    public function setUserId($id)
    {
        $this->member_id = $id;
    }

    public function getUserId()
    {
        return $this->member_id;
    }

    /**
     * 保存订单
     * @return bool
     */
    public function saveOrder()
    {
        return $this->save();
    }

    /**
     * @param \yii\base\Event $event
     * @return bool
     */
    public function finishPay(Event $event)
    {
        $this->status = self::STATUS_PAYED;
        $this->pay_time = time();

        $this->trigger(self::EVENT_BEFORE_FINISH_PAY);

        $save = $this->save();

        $this->trigger(self::EVENT_AFTER_FINISH_PAY);
        return $save;
    }


    /**
     * @param Invest $invest
     * @return array
     */
    public static function getAlreadyBuy($invest)
    {
        if ($invest instanceof Invest) {
            $id = $invest->id;
        } elseif (is_numeric($invest)) {
            $id = $invest;
        } else {
            return 0;
        }
        $config = Config::getInstance();
        $isset = $config->hasProperty('setTime.' . self::NAME_ORDER_EXPIRE);
        $timeExpire = $isset ? $config->getProperty('setTime.' . self::NAME_ORDER_EXPIRE) : 30 * 3600;
        $orders = self::find()
            ->where('invest_id=:invest_id and created_at>=:timeExpire and status=:status_unpayed', [':invest_id' => $id, ':timeExpire' => $timeExpire, ':status_unpayed' => self::STATUS_UNPAYED])
            ->orWhere('invest_id=:invest_id and status=:status_payed', [':invest_id' => $id, ':status_payed' => self::STATUS_PAYED])
            ->asArray()
            ->all();
        $sum[self::STATUS_PAYED] = $sum[self::STATUS_UNPAYED] = 0;
        foreach ($orders as $order) {
            if ($order['status'] == self::STATUS_UNPAYED) {
                $sum[self::STATUS_UNPAYED] += $order['investment_sum'];
            }
            if ($order['status'] == self::STATUS_PAYED) {
                $sum[self::STATUS_PAYED] += $order['investment_sum'];
            }
        }
        return $sum;
    }

    public static function getAlreadyBuyCount(Invest $invest)
    {
        $id = $invest->id;
        $config = Config::getInstance();
        $isset = $config->hasProperty('setTime.' . self::NAME_ORDER_EXPIRE);
        $timeExpire = $isset ? $config->getProperty('setTime.' . self::NAME_ORDER_EXPIRE) : 30 * 3600;
        return $ordersCount = self::find()
            ->where('invest_id=:invest_id and status=:status_payed', [':invest_id' => $id, ':status_payed' => self::STATUS_PAYED])
            ->count();
    }

    /**
     * 判断是否已投标
     * @param $memberId
     * @param $investId
     * @return array|bool|null|InvestList
     */
    public static function hasBuy($memberId, $investId)
    {
        $rs = self::find()
            ->where(["member_id" => $memberId])
            ->andWhere(['invest_id' => $investId])
            ->andWhere(['status' => self::STATUS_PAYED])
            ->count();
        return $rs > 0;
    }

    /**
     * notify_url return_url
     * @return array
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $notify_url
     * @param $return_url
     */
    public function setUrl($notify_url, $return_url)
    {
        $this->url['notify_url'] = $notify_url;
        $this->url['return_url'] = $return_url;
    }

    public function getName()
    {
        return $this->getInvestModel()->title;
    }

    public function getInfo()
    {
        return "购买理财产品";
    }

    /**
     *
     * @param $id
     * @return self
     */
    public static function findByIdNo($id)
    {
        return self::findOne($id);
    }


}