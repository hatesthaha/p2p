<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\user\controllers;


use common\models\User;
use modules\user\models\SignupForm;
use wanhunet\wanhunet;

class ManagementController extends BackendController
{
    public function actionIndex()
    {
        $model = User::find()->select(User::$SELECT_ROW)->asArray()->all();
        foreach ($model as &$m) {
            $m['status'] = User::get_record_status($m['status']);
            $m['created_at'] = date('Y-m-d', $m['created_at']);
        }
        return $model;
    }

    public function actionView()
    {
        $userId = wanhunet::$app->request->post('id');
        $model = User::find()->where(['id' => $userId])->select(User::$SELECT_ROW)->one();
        if ($model !== null) {
            $role = [];
            foreach (wanhunet::$app->authManager->getRolesByUser($userId) as $r) {
                $role['name'] = $r->name;
                $role['description'] = $r->description;
            }
            $roles = [];
            foreach (wanhunet::$app->authManager->getRoles() as $r) {
                $roles[] = [
                    'name' => $r->name,
                    'description' => $r->description
                ];
            }
            $model->toArray();
            $model['created_at'] = date("Y-m-d", $model['created_at']);
            return [
                'role' => $role,
                'user' => $model,
                'roles' => $roles
            ];
        } else {
            return "用户不存在";
        }
    }

    public function actionCreate()
    {
        $request = wanhunet::$app->request;
        $model = new SignupForm();
        $model->username = $request->post('username');
        $model->email = $request->post('email');
        $model->password = $request->post('password');
        $user = $model->signup();
        if ($user !== null) {
            return true;
        } else {
            return current($model->getFirstErrors());
        }
    }

    public function actionSetting()
    {
        $request = wanhunet::$app->request;
        $id = $request->post('id');
        if (($userModel = User::findOne($id)) !== null) {
            /** @var User $userModel */
            $userModel->email = $request->post('email');
            $userModel->username = $request->post('username');
            $userModel->save();
            $auth = wanhunet::$app->authManager;
            $role = $auth->getRole($request->post('role'));
            $auth->revokeAll($userModel->id);
            $auth->assign($role, $userModel->id);
            return $userModel->id == wanhunet::$app->user->id ? true : false;
        } else {
            return '用户不存在';
        }
    }
}