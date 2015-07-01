<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\asset\behaviors;


use common\models\Config;
use common\models\Debug;
use modules\asset\models\AssetMoney;
use modules\asset\models\BankCard;
use modules\invest\models\Invest;
use wanhunet\base\Behavior;
use wanhunet\base\Order;
use wanhunet\db\ActiveRecord;
use wanhunet\helpers\ErrorCode;
use wanhunet\helpers\Utils;
use wanhunet\wanhunet;
use yii\base\ErrorException;
use yii\base\Event;
use yii\db\IntegrityException;
use yii\web\ForbiddenHttpException;

/**
 * Class Asset
 * 绑定前台用户 Behavior，用于对member组件的扩展，在controller中调用时请使用 Behavior 中的方法进行操作
 * @package modules\asset\behaviors
 * @property \modules\asset\models\Asset $assetModel
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class Asset extends Behavior
{
    const NAME_PAY_PASS_FLASH = 'pay_pass_hash';
    const FUNCTION_MEMBER_AFTER_INSERT = 'memberAfterInsert';

    private $_bankCard = [];

    public $assetConfig = 'modules\asset\models\Asset';
    public $assetModel;
    public $orderClass = 'modules\asset\models\Asset';
    public $userId = null;

    /**
     * 初始化 Init()
     */
    public function init()
    {
        parent::init();
        if ($this->userId === null) {
            $this->userId = wanhunet::$app->user->getId();
        }
        $className = $this->assetConfig;
        if (($class = new $className()) instanceof ActiveRecord) {
            /** @var \wanhunet\db\ActiveRecord $class */
            if ($this->userId !== null) {
                $this->assetModel = $class->findOne($this->userId);
            }
        }
        if ($this->assetModel !== null) {
            $bankCards = empty($this->assetModel->bank_card) ? [] : json_decode($this->assetModel->bank_card);
            foreach ($bankCards as $bankCard) {
                $bankCard = unserialize($bankCard);
                if ($bankCard instanceof BankCard) {
                    /* @var \modules\asset\models\BankCard $bankcard */
                    $this->_bankCard[$bankCard->cardId] = $bankCard;
                }
            }
        }
    }

    /**
     * 钱包行为事件
     * @return array
     */
    public function events()
    {
        return Utils::eventMerge(self::className(), parent::events());
    }

    /**
     * 用户注册之后事件
     * @param $event
     */
    public function memberAfterInsert($event)
    {
        $member = $event->sender;
        /** @var \modules\member\models\Member $member */
        $asset = new \modules\asset\models\Asset();
        $asset->user_id = $member->id;
        $asset->pay_pass = $member->password_hash;
        $asset->experience_money_max = Config::getInstance()->getProperty("setIncEM.initEmMax");
        $asset->save();
    }

    /**
     * 获取银行卡数组信息
     * @param string $cardId
     * @return array|\modules\asset\models\BankCard[]
     */
    public function getBankCard($cardId = null)
    {
        if ($cardId != null && isset($this->_bankCard[$cardId])) {
            return $this->_bankCard[$cardId];
        } else {
            return $this->_bankCard;
        }
    }

    /**
     * 删除一个银行卡
     * @param BankCard $backCard
     */
    public function removeBankCard(BankCard $backCard)
    {
        unset($this->_bankCard[$backCard->cardId]);
    }

    /**
     * 添加或者设置银行卡
     * @param BankCard $backCard
     */
    public function setBankCard(BankCard $backCard)
    {
        $this->_bankCard[$backCard->cardId] = $backCard;
    }

    /**
     * 保存银行卡到数据库bank_card字段 每个银行卡是serialize 全部银行卡是 json_encode
     * @return bool
     */
    public function saveBankCard()
    {
        $setBankCard = [];
        foreach ($this->_bankCard as $bankcard) {
            if ($bankcard instanceof BankCard) {
                $setBankCard[$bankcard->cardId] = serialize($bankcard);
            }
        }
        $this->assetModel->bank_card = json_encode($setBankCard);
        return $this->assetModel->save();
    }

    /**
     * 获取支付密码散列
     * @return string
     */
    public function getPayPass()
    {
        return $this->assetModel->pay_pass;
    }

    /**
     * 用于验证支付密码
     * @param string $payPass
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function validatePayPass($payPass)
    {
        if ($rs = wanhunet::$app->getSecurity()->validatePassword($payPass, $this->getPayPass())) {
            wanhunet::$app->session->setFlash(self::NAME_PAY_PASS_FLASH, $this->getPayPass());
        }
        return $rs;
    }

    /**
     * 设置支付密码，仅是设置支付密码并未没有保存的
     * @param $payPass
     * @return string
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function setPayPass($payPass)
    {
        return $this->assetModel->pay_pass = wanhunet::$app->getSecurity()->generatePasswordHash($payPass);
    }

    /**
     * 设置并保存密码
     * @param string|null $payPass
     * @return bool
     */
    public function savePayPass($payPass = null)
    {
        if ($payPass != null) {
            $this->setPayPass($payPass);
        }
        return $this->assetModel->save();
    }


    /**
     * 获取账户余额
     * @return int
     */
    public function getMoney()
    {
        return Utils::moneyFormat($this->assetModel->money);
    }

    /**
     * 增加账户余额并不保存
     * @param int $step
     */
    public function setIncMoney($step = 0)
    {
        $this->assetModel->setIncMoney($step);
    }

    public function setIncNewMoney($step)
    {
        return $this->assetModel->setIncNewMoney($step);
    }

    /**
     * 减少账户余额并不保存
     * @param int $step
     * @param string $info
     * @throws IntegrityException
     */
    public function setDecMoney($step = 0, $info = null)
    {
        $this->assetModel->setDecMoney($step, $info);
    }


    /**
     * 保存账号余额
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function saveMoney()
    {
        return $this->assetModel->saveMoney();
    }


    /**
     * 获取体验金
     * @return int
     */
    public function getExperienceMoney()
    {
        return $this->assetModel->getExperienceMoney();
    }

    /**
     * 获取未过期的体验金
     * @return array|\modules\asset\models\AssetMoney[]
     */
    public function getExperienceMoneys()
    {
        return $this->assetModel->getExperienceMoneys();
    }

    /**
     * 获取个人最高体验金
     * @return int
     */
    public function getExperienceMoneyMax()
    {
        return $this->assetModel->experience_money_max;
    }

    /**
     * 增加个人最高体验金投资额
     * @param int $step
     */
    public function setIncExperienceMoneyMax($step = 0)
    {
        $this->assetModel->setIncExperienceMoneyMax($step);
    }

    /**
     * 保存个人最高体验金投资额
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function saveExperienceMoneyMax()
    {
        return $this->assetModel->saveExperienceMoneyMax();
    }


    /**
     * 增加体验金
     * @param int $step
     * @param null|string $action
     * @param null|int $actionUid
     */
    public function setIncExperienceMoney($step = 0, $action = null, $actionUid = null)
    {
        $this->assetModel->setIncExperienceMoney($step, $action, $actionUid);
    }

    /**
     * 减少体验金
     * @param int $step
     * @throws ForbiddenHttpException
     */
    public function setDecExperienceMoney($step = 0)
    {
        $this->assetModel->setDecExperienceMoney($step);
    }

    /**
     * 保存体验金
     * @return bool
     */
    public function saveExperienceMoney()
    {
        $this->assetModel->saveExperienceMoney();
    }

    /**
     * 确认支付密码不为空，请在控制器中处理此异常
     * @throws ErrorException
     */
    public function ensurePayPass()
    {
        $flash = wanhunet::$app->session->hasFlash(self::NAME_PAY_PASS_FLASH);
        if (!$flash) {
            throw new ErrorException("支付密码不能为空", ErrorCode::Pay_pass_empty);
        }
    }

    public function setIncExperienceMoneyInc($step = 0, $action = null, $actionUid = null)
    {
        $this->assetModel->setIncExperienceMoneyInc($step, $action, $actionUid);
    }

    /**
     * 账单支付
     * @param Order $order
     * @param BankCard $bankCard
     * @return string
     */
    public function payOrderWithBankCard(Order $order, BankCard $bankCard)
    {
        $pay = wanhunet::app()->pay;
        $pay->setUrl($order->getUrl());
        $pay->user_id = wanhunet::$app->user->id;
        $pay->busi_partner = 101001;
        $pay->no_order = $order->getOrderId();
        $pay->money_order = $order->getPrice();
        $pay->name_goods = $order->getName();
        $pay->info_order = $order->getInfo();
        $pay->card_no = $bankCard->cardId;
        $pay->acct_name = wanhunet::app()->member->idcard_name;
        $pay->id_no = wanhunet::app()->member->idcard;
        $pay->no_agree = '';
        return $pay->payApi();
    }

    public function payOrderWithBalance(Order $order)
    {
        /** @var \modules\invest\models\InvestList $order */
        $this->ensurePayPass();
        $price = $order->getPrice();
        $orderStatus = $order->getStatus();
        if ($orderStatus != Order::STATUS_UNPAYED) {
            throw new ErrorException('账单已支付', ErrorCode::Pay_had_pay);
        }
        $member = wanhunet::app()->member;
        if ($order->getType() == Invest::TYPE_MONEY) {
            $orderName = mb_substr($order->getName(), 0, 5, "utf-8");
            if (mb_strlen($order->getName(), "utf-8") > 5) {
                $orderName .= "...";
            }
            $orderName .= "投资";
            $member->setDecMoney($price, $orderName);
            $save = $member->saveMoney();
            if ($save) {
                return $order->finishPay(new Event());
            }
        } else {
            $member->setDecExperienceMoney($price);
            $save = $member->saveExperienceMoney();
            $order->finishPay(new Event());
        }
        return $save;
    }

}