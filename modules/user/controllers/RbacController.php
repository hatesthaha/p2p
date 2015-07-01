<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\user\controllers;


use wanhunet\wanhunet;

class RbacController extends BackendController
{
    public function actionRoles()
    {
        $roleds = wanhunet::$app->authManager->getRoles();
        $roledsArray = [];
        foreach ($roleds as $key => $value) {
            $roledsArray[] = $value;
        }
        return $roledsArray;
    }

    public function actionRoleView()
    {
        $roleName = wanhunet::$app->request->post('role_name');
        $auth = wanhunet::$app->authManager;
        $permissionsOfRole = $auth->getPermissionsByRole($roleName);
        $permissions = [];
        foreach ($permissionsOfRole as $key => $p) {
            $permissions[] = $key;
        }
        $role = wanhunet::$app->authManager->getRole($roleName);
        $allPerissions = $auth->getPermissions();
        $allPerissionsArray = [];
        foreach ($allPerissions as $key => $p) {
            $allPerissionsArray[$key]['description'] = $p->description;
            $allPerissionsArray[$key]['name'] = $p->name;
            if (in_array($key, $permissions)) {
                $allPerissionsArray[$key]['check'] = 'checked';
            } else {
                $allPerissionsArray[$key]['check'] = '';
            }
        }
        return [
            'role' => $role,
            'allPerissions' => $allPerissionsArray,
            'perissions' => $permissions
        ];
    }

    public function actionRoleCreate()
    {
        $roleName = wanhunet::$app->request->post('name');
        $description = wanhunet::$app->request->post('description');
        $auth = wanhunet::$app->authManager;
        $role = $auth->createRole($roleName);
        $role->description = $description;
        try {
            return $auth->add($role);
        } catch (\ErrorException $e) {
            return '请检测您的角色名是否有重复';
        }
    }

    public function actionRoleSetting()
    {
        $auth = wanhunet::$app->authManager;
        $roleName = wanhunet::$app->request->post('name');
        $role = wanhunet::$app->authManager->getRole($roleName);
        $auth->removeChildren($role);
        $permissions = wanhunet::$app->request->post('permissions');
        $rs = [];
        foreach ($permissions as $permission) {
            try {
                $permissionModel = $auth->getPermission($permission);
                $auth->addChild($role, $permissionModel);
                $rs[] = true;
            } catch (\Exception $e) {
                $rs[] = false;
            }
        }
        return $rs;
    }

    public function actionRoleDelete()
    {
        $auth = wanhunet::$app->authManager;
        $roleName = wanhunet::$app->request->post('role_name');
        $role = $auth->getRole($roleName);
        return $auth->remove($role);
    }

    public function actionPermissions()
    {
        return wanhunet::$app->authManager->getPermissions();
    }

}