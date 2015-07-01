<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\asset\models;


use common\models\Config;
use common\models\Debug;
use modules\member\models\Member;
use wanhunet\base\Order;
use wanhunet\db\ActiveRecord;
use wanhunet\helpers\Utils;
use wanhunet\wanhunet;
use yii\base\Event;

/**
 * Class AssetMoney
 * @package modules\asset\models
 *
 * @property integer $id
 * @property integer $user_id
 * @property double $step
 * @property integer $status
 * @property integer $type
 * @property string $action
 * @property integer $action_uid
 * @property String $llinfo
 * @property integer $created_at
 * @property integer $updated_at
 * @property Member $actionUid
 * @property integer $member
 *
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class AssetMoney extends ActiveRecord implements Order
{
    const STATUS_EXPIRE = 10;
    const STATUS_INC = 20;
    const STATUS_DEC = 30;
    const STATUS_MONEY_UNPAYED = 50;
    const STATUS_FREEZE = 40;

    const TYPE_MONEY = 10;
    const TYPE_EXPERIENCE_MONEY = 20;
    const TYPE_EXPERIENCE_MONEY_MAX = 30;
    const TYPE_MENTION = 40;

    const EVENT_FINISH_PAY = 'AssetMoneyFinishPay';

    public $url;

    public static function  get_status($status)
    {
        switch ($status) {
            case self::STATUS_INC:
                return '增长';
                break;
            case self::STATUS_DEC:
                return '减少';
                break;
            case self::STATUS_MONEY_UNPAYED:
                return '未付款';
                break;
            case self::STATUS_FREEZE:
                return '冻结';
                break;
            case self::STATUS_ORDER_MADE:
                return '提现已建立';
                break;
            case self::STATUS_PAYED:
                return '订单已支付';
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

    public static function get_type($type)
    {
        switch ($type) {
            case self::TYPE_MONEY:
                return '余额';
                break;
            case self::TYPE_EXPERIENCE_MONEY:
                return '体验金';
                break;
            case self::TYPE_EXPERIENCE_MONEY_MAX:
                return '体验金上限';
                break;
            case self::TYPE_MENTION:
                return '提现申请';
                break;
            default:
                return $type;
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%asset_money}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'step'], 'required'],
            [['user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['step'], 'number']
        ];
    }

    public function behaviors()
    {
        return Utils::behaviorMerge(self::className(), parent::behaviors());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'step' => 'step',
            'status' => 'Status',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 获取未过期的体验金总额
     * @param int $userId
     * @return int
     */
    public static function getExperienceMoney($userId = null)
    {
        if (is_null($userId)) {
            $userId = wanhunet::$app->user->getId();
        }
        $allEM = self::find()
            ->where(['type' => self::TYPE_EXPERIENCE_MONEY])
            ->andWhere(['>=', 'created_at', time() - 30 * 24 * 3600])
            ->andWhere(['user_id' => $userId])
            ->asArray()
            ->all();
        $sum = 0;
        foreach ($allEM as $em) {
            if ($em['status'] == self::STATUS_INC) {
                $sum += $em['step'];
            }
            if ($em['status'] == self::STATUS_DEC) {
                $sum -= $em['step'];
            }
        }
        return $sum;
    }


    /**
     * 获取未过期的体验金条目
     * @param int $userId
     * @return array|AssetMoney[]
     */
    public static function getExperienceMoneys($userId = null)
    {
        if (is_null($userId)) {
            $userId = wanhunet::$app->user->getId();
        }
        $allEM = self::find()
            ->where(['type' => self::TYPE_EXPERIENCE_MONEY])
            ->andWhere(['>=', 'created_at', time() - 30 * 24 * 3600])
            ->andWhere(['user_id' => $userId])
            ->all();
        return $allEM;
    }

    public function getActionUid()
    {
        return $this->hasOne(Member::className(), ['id' => 'action_uid']);
    }

    /**
     * @return \modules\member\models\Member|\modules\asset\behaviors\Asset
     */
    public function getMemberModel()
    {
        return \modules\member\models\Member::findOne($this->action_uid);
    }

    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => "user_id"]);
    }

    public function getActionName()
    {
        $config = Config::getInstance();
        $actions = $config->getProperty('assetLog');
        return isset($actions[$this->action]) ? $actions[$this->action] : "赠送";
    }

    /**
     * @return null|static|Member
     */
    public function getActionIdModel()
    {
        return Member::findOne($this->action_uid);
    }

    public function getStep()
    {
        return sprintf("%.2f", $this->step);
    }

    public function finishPay(Event $event)
    {
        /** @var \wanhunet\components\PayEvent $event */
        $data = $event->rs;
        $notifyResp = $data['notifyResp'];
        Debug::add($notifyResp);
        $this->status = self::STATUS_INC;
        $this->save();
        /** @var Asset $asset */
        $asset = Asset::find()->where(['user_id' => $this->user_id])->one();
        if ($asset !== null) {
            $asset->money = $this->step + $asset->money;
            $asset->saveMoney();
        }
        $event->rs['user_id'] = $this->user_id;
        $this->trigger(self::EVENT_FINISH_PAY, $event);
    }

    public function getInfo()
    {
        return "充值";
    }

    public function getName()
    {
        return "充值";
    }


    /**
     * @param $id
     * @return AssetMoney
     */
    public static function findByIdNo($id)
    {
        return self::findOne($id);
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
        return $this->step;
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
        return $this->type;
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
     * 保存订单
     * @return mixed
     */
    public function saveOrder()
    {
        return $this->save();
    }

    /**
     * 设置产品ID
     * @param int $id
     * @return mixed
     */
    public function setProductId($id)
    {
        return true;
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

    /**
     * 设置用户ID
     * @param int $id
     * @return mixed
     */
    public function setUserId($id)
    {
        $this->user_id = $id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }


}