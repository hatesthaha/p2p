<?php

namespace modules\asset;

/**
 * Class Module
 * 钱包模块
 * @package modules\asset
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class Module extends \wanhunet\base\Module
{
    public $controllerNamespace = 'modules\asset\controllers';

    public function init()
    {
        parent::init();

    }

    protected $_menus =
        [
            'child' => [
                [
                    '资金动态', 'asset/finance',
                    'child' =>
                        [
                            ['用户资金', 'asset/finance/index'],
                        ]
                ],
                [
                    '充值管理', 'asset/recharge',
                    'child' =>
                        [
                            ['充值导出', 'asset/recharge/export', self::MENU_HIDE],
                            ['充值列表', 'asset/recharge/index'],
                        ]
                ],
                [
                    '提现管理', 'asset/withdraw',
                    'child' =>
                        [
                            ['提现记录', 'asset/withdraw/index'],
                            ['提现核对', 'asset/withdraw/check', self::MENU_HIDE],
                            ['提现初审', 'asset/withdraw/first-trial'],
                            ['提现终审', 'asset/withdraw/final-trial'],
                        ]
                ],
                [
                    '体验金管理', 'asset/experience',
                    'child' =>
                        [
                            ['资金记录', 'asset/experience/index'],
                            ['体验金设置', 'asset/experience/setting'],
                            ['体验金操作', 'asset/experience/new-em'],
                        ]
                ],
            ]
        ];
}
