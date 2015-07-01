<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace frontend\controllers;


use modules\member\models\LoginForm;
use modules\member\models\MemberOther;
use wanhunet\helpers\Utils;
use wanhunet\wanhunet;
use yii\helpers\Url;

class WechatController extends FrontendController
{
    public function actionLogin()
    {
        Utils::ensureOpenId();
        $request = wanhunet::$app->request;
        if (!wanhunet::$app->user->getIsGuest()) {
            return $this->redirect(Url::to(['site/main']));
        }
        if ($request->isPost) {
            $login = new LoginForm($request->post());
            if ($login->login()) {
                return $this->goBack([
                    'info' => '免登录模式开启成功',
                ], Url::to(['site/main']));
            } else {
                return $this->goBack([
                    'info' => current($login->getFirstErrors()),
                    'phone' => $request->post('username'),
                ], Url::to(['login', 'open_id' => $_POST['open_id']]));
            }
        }
        return $this->view('login');
    }

    public function actionOff()
    {
        Utils::ensureOpenId();
        $request = wanhunet::$app->request;
        if ($request->isPost) {
            if (
                ($model = MemberOther::findOne(['row' => $request->post('open_id'), 'table' => MemberOther::TABLE_WECHAT])) !== null
            ) {
                /** @var MemberOther $model */
                $model->row = '';
                $model->save();
            }
            wanhunet::$app->user->logout();
            return $this->goBack([
                'info' => "解绑成功"
            ], Url::to(['site/signin']));
        }
        return $this->view('off');
    }


    public function actionWechat()
    {
        if (wanhunet::app()->wechat->checkSignature()) {
            echo $echoStr = $_GET["echostr"];
        }
    }

    public function actionCreateMenu()
    {
        $url = wanhunet::$app->urlManager;
        $wechat = wanhunet::app()->wechat;
        $auth = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx32814d588c44c17c&redirect_uri=REDIRECT_URI&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
        $aa = wanhunet::app()->wechat->createMenu([
            [
                'type' => 'view',
                'name' => '进入玖信贷',
                'url' => $wechat->getOauth2AuthorizeUrl($url->createAbsoluteUrl(['site/enter']))
            ],
            [
                'name' => '玖信特权',
                'sub_button' =>
                    [
                        [
                            'type' => 'view',
                            'name' => '主人发特权',
                            'url' => $wechat->getOauth2AuthorizeUrl($url->createAbsoluteUrl(['text/share']))
                        ],
                        [
                            'type' => 'view',
                            'name' => '注册拿特权',
                            'url' => $url->createAbsoluteUrl(['site/signup'])
                        ],

                    ]
            ],
            [
                'name' => '玖信服务',
                'sub_button' =>
                    [
                        [
                            'type' => 'view',
                            'name' => '免登录功能',
                            'url' => $wechat->getOauth2AuthorizeUrl($url->createAbsoluteUrl(['wechat/login']))
                        ],
                        [
                            'type' => 'view',
                            'name' => '取消免登录',
                            'url' => $wechat->getOauth2AuthorizeUrl($url->createAbsoluteUrl(['wechat/off']))
                        ],

                    ]
            ]
        ]);
        var_dump($aa);
    }

}