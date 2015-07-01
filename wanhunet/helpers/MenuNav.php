<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace wanhunet\helpers;


use wanhunet\base\Module;
use wanhunet\wanhunet;

class MenuNav
{
    protected $_permissions = [];
    protected $allNav = [];


    public function getMeunNav()
    {
        $auth = wanhunet::$app->authManager;
        $this->_permissions = $auth->getPermissionsByUser(wanhunet::$app->user->getId());
        $menus = Utils::getUserModulesOfMenus();
        $this->_buildAllNav($menus);
        $this->_buildNav($this->allNav);
        $this->_formatNav($this->allNav);
        return $this->allNav;
    }

    private function _buildNav(&$datas)
    {
        foreach ($datas as $key => &$data) {
            if (is_array($data)) {
                if (isset($data['child'])) {
                    if (isset($data[0]) and is_string($data[0])) {
                        $this->_buildNav($data['child']);
                    }
                } else {
                    if (!array_key_exists($data[1], $this->_permissions) or (isset($data[2]) and $data[2] == Module::MENU_HIDE)) {
                        unset($datas[$key]);
                    }
                }
            }
        }
    }

    private function _formatNav(&$datas)
    {
        foreach ($datas as $key => &$data) {
            if (isset($data['child']) and count($data['child']) <= 0) {
                unset($datas[$key]);
            } else {
                if (isset($data['child']) and is_array($data['child'])) {
                    foreach ($data['child'] as &$child) {
                        $child['sref'] = 'app.' . str_replace('/', '_', $child[1]);
                    }
                }
            }
        }
    }

    private function _buildAllNav($datas)
    {
        foreach ($datas as $key => $data) {
            if (is_array($data)) {
                if (isset($data['child'])) {
                    if (isset($data[0]) and is_string($data[0])) {
                        $this->allNav[] = $data;
                    } else {
                        $this->_buildAllNav($data['child']);
                    }
                }
            }
        }
    }
}