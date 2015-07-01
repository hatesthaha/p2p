<?php

namespace modules\cms;


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

    public $controllerNamespace = 'modules\cms\controllers';

    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }


    protected $_menus =
        [
            'child' => [
                [
                    '文章管理', 'cms/post',
                    'child' =>
                        [
                            ['文章列表', 'cms/post/index'],
                            ['文章添加', 'cms/post/create'],

                            ['文章详情', 'cms/post/view', self::MENU_HIDE],
                            ['文章删除', 'cms/post/delete', self::MENU_HIDE],
                            ['文章更新', 'cms/post/update', self::MENU_HIDE],
                            ['文章上传', 'cms/post/upload', self::MENU_HIDE]
                        ]
                ],
                [
                    '栏目管理', 'cms/category',
                    'child' =>
                        [
                            ['栏目列表', 'cms/category/index'],
                            ['栏目添加', 'cms/category/create'],

                            ['栏目详情', 'cms/category/view', self::MENU_HIDE],
                            ['栏目更新', 'cms/category/update', self::MENU_HIDE],
                            ['栏目删除', 'cms/category/delete', self::MENU_HIDE],
                        ]
                ]
            ],

        ];

}
