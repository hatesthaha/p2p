<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\member\behaviors;


use frontend\models\SignupForm;
use modules\member\models\VerificationCode;
use wanhunet\base\Behavior;
use wanhunet\helpers\Utils;
use wanhunet\wanhunet;
use yii\base\Event;

class Member extends Behavior
{
    const FUNCTION_SAVE_MEMBER = 'saveMember';

    public function events()
    {
        return Utils::eventMerge(self::className(), parent::events());
    }

    public function saveMember($event)
    {
        /** @var \modules\member\models\VerificationCode $verificationCode */
        /** @var Event $event */
        $verificationCode = $event->sender;
        $phone = $verificationCode->field;
        if (wanhunet::$app->user->isGuest) {
            $model = new SignupForm();
            $model->load([
                'username' => $phone,
                'phone' => $phone,
                'password' => wanhunet::$app->request->post('password')
            ]);
            $model->signup();
        }

    }

}