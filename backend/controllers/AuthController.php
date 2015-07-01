<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace backend\controllers;


use common\models\LoginForm;
use common\models\User;
use wanhunet\helpers\MenuNav;
use wanhunet\wanhunet;
use yii\rest\Controller;

class AuthController extends Controller
{
    public function init()
    {
        parent::init();

        $request = wanhunet::$app->request;
        if (!$request->isGet) {
            $params = json_decode(file_get_contents('php://input'), true);
            $request->setBodyParams($params);
        }
    }

    /**
     * 用户登陆控制器
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!wanhunet::$app->user->isGuest) {
            return !wanhunet::$app->user->isGuest;
        }
        $model = new LoginForm(wanhunet::$app->request->post());
        if ($model->login()) {
            return true;
        } else {
            return current($model->getFirstErrors());
        }
    }

    public function actionCheckAccess()
    {
        return wanhunet::$app->user->isGuest;
    }

    public function actionGetUserInfo()
    {
        $user = wanhunet::$app->user;
        $userArray = User::find()
            ->where(['id' => $user->id])
            ->select(['username', 'id'])
            ->asArray()
            ->one();
        $userArray['role'] = current(wanhunet::$app->authManager->getRolesByUser($user->id));
        return $userArray;
    }

    /**
     * 用户登出控制器
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        wanhunet::$app->user->logout();

        return $this->goHome();
    }

    public function actionMeunNav()
    {
        return (new MenuNav())->getMeunNav();
    }

    public function actionUeditor()
    {
        return $this->renderAjax('/site/ueditor');
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
}