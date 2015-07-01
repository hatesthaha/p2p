<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\asset\behaviors;


use modules\asset\models\AssetMoney;
use wanhunet\base\Behavior;
use wanhunet\helpers\Utils;
use wanhunet\wanhunet;

class Log extends Behavior
{
    const FUNCTION_BEFORE_UPDATE = 'beforeUpdate';
    const FUNCTION_BEFORE_INSERT = 'beforeInsert';

    public function events()
    {
        return Utils::eventMerge(self::className(), parent::events());
    }

    public function beforeUpdate()
    {

    }

    public function beforeInsert($event)
    {
        /** @var \yii\base\Event $event */
        /** @var \modules\asset\models\AssetMoney $assetMoney */
        $assetMoney = $event->sender;
        if ($assetMoney instanceof AssetMoney) {
            $action = $assetMoney->action;
            $action_uid = $assetMoney->action_uid;
            if (empty($action)) {
                $assetMoney->action = wanhunet::$app->controller->getRoute();
            }
            if (empty($action_uid)) {
                $assetMoney->action_uid = wanhunet::$app->user->getId();
            }
        }
    }

}