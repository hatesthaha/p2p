<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\member\controllers;


use modules\member\models\VerificationCode;

class VerificationController extends BackendController
{
    public function actionPhone()
    {
        return VerificationCode::find()->where(['type' => VerificationCode::TYPE_PHONE])->all();

    }

    public function actionEmail()
    {
        return VerificationCode::find()->where(['type' => VerificationCode::TYPE_EMAIL])->all();
    }
}