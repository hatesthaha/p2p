<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace frontend\controllers;


use wanhunet\base\Controller;
use wanhunet\wanhunet;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class FrontendController extends Controller
{
    public $layout = 'layout';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['get-captcha', 'signup', 'signin', 'signup-verify', 'login', 'off', 'enter', 'pay-notify', 'notify', 'list', 'view', 'wechat', 'create-menu'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function () {
                    wanhunet::$app->getSession()->setFlash("errors", ['info' => '请先登录']);
                    return $this->redirect(Url::to(['site/signin']));
                }
            ],
        ];
    }

    public function goBack($params = null, $defaultUrl = null)
    {
        if ($params !== null) {
            wanhunet::$app->getSession()->setFlash("errors", $params);
        }
        return $this->redirect($defaultUrl);
    }

}