<?php

namespace modules\user;


/**
 * Class Module
 * 内容管理模块
 * @package modules\cms
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class Module extends \wanhunet\base\Module
{

    public $controllerNamespace = 'modules\user\controllers';

    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }


    protected $_menus =
        [
            'child' => [
                [
                    '权限管理', 'user/rbac',
                    'child' =>
                        [
                            ['角色列表', 'user/rbac/roles'],

                            ['权限列表', 'user/rbac/permissions', self::MENU_HIDE],
                            ['角色查看', 'user/rbac/role-view', self::MENU_HIDE],
                            ['角色添加', 'user/rbac/role-create', self::MENU_HIDE],
                            ['角色删除', 'user/rbac/role-delete', self::MENU_HIDE],
                            ['角色设置', 'user/rbac/role-setting', self::MENU_HIDE],
                        ]
                ],
                [
                    '用户管理', 'user/management',
                    'child' =>
                        [
                            ['用户列表', 'user/management/index'],

                            ['角色查看', 'user/management/view', self::MENU_HIDE],
                            ['角色添加', 'user/management/create', self::MENU_HIDE],
                            ['角色删除', 'user/management/delete', self::MENU_HIDE],
                            ['角色设置', 'user/management/setting', self::MENU_HIDE],
                        ]
                ]
            ]
        ];

}
