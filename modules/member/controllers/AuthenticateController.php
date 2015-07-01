<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\member\controllers;


use modules\member\models\Member;
use wanhunet\wanhunet;

class AuthenticateController extends BackendController
{

    public function actionIdcard()
    {
        $member = Member::find();
        $map = [
            'idcard_status' => Member::STATUS_HAVE_AUTH
        ];
        return $member->select(Member::$SELECT_ROW)->where($map)->all();
    }

    public function actionIdcardDo()
    {
        $request = wanhunet::$app->request;
        return Member::updateAll(['idcard_status' => Member::STATUS_NOT_HAVE_AUTH, 'idcard' => ''], ['id' => $request->post("id")]);
    }

    public function actionEmail()
    {
        $member = Member::find();
        $map = [
            'email_status' => Member::STATUS_HAVE_AUTH
        ];
        return $member->select(Member::$SELECT_ROW)->where($map)->all();
    }

    public function actionEmailDo()
    {
        $request = wanhunet::$app->request;
        return Member::updateAll(['email_status' => Member::STATUS_NOT_HAVE_AUTH, 'email' => ''], ['id' => $request->post("id")]);
    }

    public function actionView()
    {
        $id = wanhunet::$app->request->post('id');
        return Member::find()->select(Member::$SELECT_ROW)->where(['id' => $id])->one();
    }

}