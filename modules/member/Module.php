<?php

namespace modules\member;


/**
 * Class Module
 * 前台用户模块
 * @package modules\member
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class Module extends \wanhunet\base\Module
{

    public $controllerNamespace = 'modules\member\controllers';

    public function init()
    {
        parent::init();
    }


    protected $_menus =
        [
            'child' => [
                [
                    '会员管理', 'member/members',
                    'child' =>
                        [
                            ['会员列表', 'member/members/index'],
                            ['锁定列表', 'member/members/blacklist'],

                            ['会员锁定', 'member/members/lock', self::MENU_HIDE],
                            ['会员解锁', 'member/members/unlock', self::MENU_HIDE],
                            ['会员详情', 'member/members/view', self::MENU_HIDE],
                            ['会员好友', 'member/members/friends', self::MENU_HIDE],
                        ]
                ],
                [
                    '认证审核', 'member/authenticate',
                    'child' =>
                        [
                            ['认证身份', 'member/authenticate/idcard'],
                            ['认证邮箱', 'member/authenticate/email'],

                            ['认证信息', 'member/authenticate/view', self::MENU_HIDE],
                            ['认证身份', 'member/authenticate/idcard-do', self::MENU_HIDE],
                            ['认证邮箱', 'member/authenticate/email-do', self::MENU_HIDE],
                        ]
                ],
                [
                    '验证码管理', 'member/verification',
                    'child' =>
                        [
                            ['手机验证码', 'member/verification/phone'],
                            ['邮箱验证码', 'member/verification/email'],
                        ]
                ],
            ]
        ];
}
