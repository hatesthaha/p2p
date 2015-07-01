<?php

namespace modules\invest;

/**
 * Class Module
 * 投资产品模块
 * @package modules\invest
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class Module extends \wanhunet\base\Module
{
    public $controllerNamespace = 'modules\invest\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    protected $_menus =
        [
            'child' => [
                [
                    '标管理', 'invest/product',
                    'child' =>
                        [
                            ['标列表', 'invest/product/index'],
                            ['标添加', 'invest/product/create'],

                            ['标查看', 'invest/product/view', self::MENU_HIDE],
                            ['标审核', 'invest/product/check', self::MENU_HIDE],
                            ['标解除审核', 'invest/product/uncheck', self::MENU_HIDE],
                            ['标修改', 'invest/product/update', self::MENU_HIDE],
                            ['标删除', 'invest/product/delete', self::MENU_HIDE],
                        ]
                ],
                [
                    '投资列表', 'invest/list',
                    'child' =>
                        [
                            ['投资列表', 'invest/list/index'],
                            ['返息月表', 'invest/list/month-index'],
                            ['体验标返息', 'invest/list/tiyan-index'],

                            ['投资查看', 'invest/list/view', self::MENU_HIDE],
                            ['体验标返息', 'invest/list/return-rate', self::MENU_HIDE],
                            ['返息每月', 'invest/list/return-rate-month', self::MENU_HIDE],
                        ]
                ],
                [
                    '活动管理', 'invest/activity',
                    'child' =>
                        [
                            ['活动列表', 'invest/activity/index'],
                            ['活动添加', 'invest/activity/create'],

                            ['活动查看', 'invest/activity/view', self::MENU_HIDE],
                            ['活动修改', 'invest/activity/update', self::MENU_HIDE],
                            ['活动删除', 'invest/activity/delete', self::MENU_HIDE],
                        ]
                ],
            ]
        ];
}
