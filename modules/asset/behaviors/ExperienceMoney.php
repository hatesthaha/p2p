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
use modules\member\models\Member;
use modules\member\models\MemberOther;
use wanhunet\base\Behavior;
use wanhunet\helpers\Utils;
use wanhunet\wanhunet;
use yii\base\Event;

/**
 * Class ExperienceMoney
 * @package modules\asset\behaviors
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class ExperienceMoney extends Behavior
{

    const FUNCTION_INC_EM = 'setIncEM';
    const FUNCTION_INC_FIRST_MONEY = 'setIncFirstMoney';
    const FUNCTION_INC_FIRST_IDCARD = 'setIncFirstIdcard';
    const FUNCTION_INC_PHONE = 'setIncPhone';
    const FUNCTION_INC_EMAIL = 'setIncEmail';
    const FUNCTION_INT_BANGDING = 'setIncBangding';

    /**
     * @return array
     */
    public function events()
    {
        return Utils::eventMerge(self::className(), parent::events());
    }

    /**
     * 提高体验金
     * @param $event
     * @param $action
     * @param $actionUid
     */
    public function setIncEM($event, $action = null, $actionUid = null)
    {
        $eventName = self::FUNCTION_INC_EM . '.' . $event->name;
        $config = Config::getInstance();
        if ($config->hasProperty($eventName)) {
            $step = $config->$eventName;
            wanhunet::app()->member->setIncExperienceMoney($step, $action, $actionUid);
            wanhunet::app()->member->saveExperienceMoney();
        }
    }
    /**
     * 提高体验金上限
     * @param $event
     * @param $action
     * @param $actionUid
     */
    public function setIncEMMax($event, $action = null, $actionUid = null)
    {
        $eventName = self::FUNCTION_INC_EM . '.' . $event->name;
        $config = Config::getInstance();
        if ($config->hasProperty($eventName)) {
            $step = $config->$eventName;
            wanhunet::app()->member->setIncExperienceMoneyMax($step);
            wanhunet::app()->member->saveExperienceMoneyMax();
        }
    }

    /**
     * 首次充钱
     * @param $event
     */
    public function setIncFirstMoney($event)
    {
        $rs = $event->rs;
        $assetOne = AssetMoney::find()
            ->where(['user_id' => $rs['user_id']])
            ->andWhere(['type' => AssetMoney::TYPE_MONEY])
            ->andWhere(['status' => AssetMoney::STATUS_INC])
            ->count();
        if ($assetOne == 1) {
            Debug::add('asset' . $assetOne);
            $assm = new AssetMoney();
            $assm->action = wanhunet::$app->controller->getRoute();
            $assm->step = Config::getInstance()->getProperty('setIncEM.firstMoney');
            $assm->user_id = $rs['user_id'];
            $assm->action_uid = $rs['user_id'];
            $assm->status = AssetMoney::STATUS_INC;
            $assm->type = AssetMoney::TYPE_EXPERIENCE_MONEY;
            $assm->save();

            $assmax = new AssetMoney();
            $assmax->action = wanhunet::$app->controller->getRoute();
            $assmax->step = Config::getInstance()->getProperty('setIncEM.firstMoney');
            $assmax->user_id = $rs['user_id'];
            $assmax->action_uid = $rs['user_id'];
            $assmax->status = AssetMoney::STATUS_INC;
            $assmax->type = AssetMoney::TYPE_EXPERIENCE_MONEY_MAX;
            $assmax->save();

        }
    }

    /**
     * 首次验证邮箱
     * @param $event
     */
    public function setIncEmail($event)
    {
        $member = wanhunet::app()->member;
        if ($member instanceof Member) {
            if ($member->email_status == Member::STATUS_NOT_HAVE_AUTH) {
                $this->setIncEM((new Event(['name' => 'verify_success_email'])));
                $this->setIncEMMax((new Event(['name' => 'verify_success_email'])));
            }
        }
    }

    /**
     * 第一次保存身份证
     * @param $event
     */
    public function setIncFirstIdcard($event)
    {
        $member = $event->sender;
        /** @var \modules\member\models\Member $member */
        $oldIdCard = $member->getOldAttribute("idcard");
        if (empty($oldIdCard)) {
            $this->setIncEM((new Event(['name' => 'firstIdcard'])));
            $this->setIncEMMax((new Event(['name' => 'firstIdcard'])));
        }
    }

    /**
     * 首次验证手机
     * @param $event
     */
    public function setIncPhone($event)
    {
        /** @var Event $event */
        /** @var \modules\member\models\Member $member */
        $member = wanhunet::app()->member;
        if (($parentModel = Member::findByInvitation($member->parent_id)) !== null) {
            $asset = new Asset(['userId' => $parentModel->id]);
            $config = Config::getInstance();
            $asset->setIncExperienceMoney($config->getProperty('setIncEM.invitationParent'), 'site/invitationParent');
            if ($parentModel->findFriends(true) == 9) {
                $asset->setIncExperienceMoney($config->getProperty('setIncEM.invitationParentConut9'), 'site/invitationParentConut9');
                $this->setIncEM(new Event(['name' => 'invitationConut9']));
            }
            $asset->saveExperienceMoney();
        }
        $this->setIncEM(new Event(['name' => 'verify_success_phone']));
        $this->setIncEMMax(new Event(['name' => 'verify_success_phone']));

    }

    /**
     * 首次绑定其他信息
     * @param $event
     */
    public function setIncBangding($event)
    {
        /** @var Event $event */
        /** @var \modules\member\models\MemberOther $memberOther */
        $memberOther = $event->sender;
        if (!$memberOther instanceof MemberOther) {
            return;
        }
        $table = $memberOther->table;
        switch ($table) {
            case MemberOther::TABLE_JIUXIN:
                $this->setIncEM(new Event(['name' => 'jiuxin']));
                $this->setIncEMMax(new Event(['name' => 'jiuxin']));
                break;
            case MemberOther::TABLE_WECHAT:
                $this->setIncEM(new Event(['name' => 'wechat']), 'wechat/login');
                break;
            default:
                # code...
                break;
        }
    }
}