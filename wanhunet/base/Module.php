<?php


namespace wanhunet\base;

/**
 * Class Module
 * 继承 \yii\base\Module 用于丰富\yii\base\Module 在Module模块操作中请继承来自此类而非\yii\base\Module
 * @property array $_menus 用于配置 RBAC 以及 administrator's menu
 * @package wanhunet\base
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 */
class Module extends \yii\base\Module
{
    /**
     * 模块的后台菜单 用于模块安装时候生成 Permission of RBAC, 以及卸载时删除 Permission of RBAC
     * 以及生成每个用户所属权限的后台管理菜单
     * 请参照以下 [key => value] 的格式进行配置
     * 配置方式可继承此类并 overwrite menus getter 或者 overwrite [[wanhunet\base::_menus|$_menus]]
     * 或者通过 config['modules']['modules name']['menus'] 进行配置
     * @example
     * [
     *     ["标题信息",'action and route',[
     *          ["标题信息",'action and route'],
     *     ]]
     * ]
     * @example
     * // ['内容管理', 'cms', [
     * //     ['文章管理', 'cms/post', [
     * //         ['文章列表', 'cms/post/index'],
     * //         ['文章详情', 'cms/post/view'],
     * //         ['文章添加', 'cms/post/create'],
     * //         ['文章删除', 'cms/post/delete'],
     * //     ]],
     * //     ['栏目管理', 'cms/category', [
     * //         ['栏目列表', 'cms/category/index'],
     * //         ['栏目详情', 'cms/category/view'],
     * //         ['栏目添加', 'cms/category/create'],
     * //         ['栏目删除', 'cms/category/delete'],
     * //     ]],
     * // ]]
     */
    const  MENU_HIDE = 0;
    protected $_menus = [];

    /**
     * The getter of $_menus
     * @return array
     */
    public function getMenus()
    {
        return $this->_menus;
    }

    /**
     * The setter of $_menus
     * @param array $menus
     */
    public function setMenus($menus)
    {
        $this->_menus = $menus;
    }
}