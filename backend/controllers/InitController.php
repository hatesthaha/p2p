<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace backend\controllers;


use modules\cms\models\Category;
use wanhunet\base\Controller;
use wanhunet\helpers\Utils;
use wanhunet\wanhunet;

class InitController extends Controller
{
    public function behaviors()
    {
        return [];
    }

    public $parentPermission = [];
    public $auth_item = [];
    public $auth_child = [];

    public function actionIndex()
    {
        $this->actionRbac();
    }
    public function actionRbac()
    {
        wanhunet::$app->authManager->removeAll();
        $menusTree = Utils::getUserModulesOfMenus();
        $this->addPermission($menusTree);
//        print_r($menusTree);
//        $this->actionRole();
//        print_r($this->auth_item);
//        print_r($this->auth_child);
        $this->addItem();
        $this->addChild();
        $this->actionRole();

    }

    private function addPermission($datas)
    {
        foreach ($datas as $key => $data) {
            if (is_array($data)) {
                if (isset($data['child'])) {
                    if (isset($data[0]) and is_string($data[0])) {
                        $this->auth_item[] = ['name' => $data[1], 'd' => $data[0]];
                        $this->parentPermission[] = $data[1];
                    }
                    $this->addPermission($data['child']);
                    array_pop($this->parentPermission);
                } else {
                    $this->auth_item[] = ['name' => $data[1], 'd' => $data[0]];
                    $this->auth_child[][end($this->parentPermission)] = $data[1];
                }
            }
        }
    }

    private function addItem()
    {
        $auth = wanhunet::$app->authManager;
        foreach ($this->auth_item as $item) {
            try {
                $permission = $auth->createPermission($item['name']);
                $permission->description = $item['d'];
                $auth->add($permission);
            } catch (\Exception $e) {
                echo "已存在<br />";
            }
        }
    }

    private function addChild()
    {
        $auth = wanhunet::$app->authManager;
        foreach ($this->auth_child as $child) {
            foreach ($child as $key => $vo) {
                try {
                    $p = $auth->getPermission($key);
                    $a = $auth->getPermission($vo);
                    $auth->addChild($p, $a);
                } catch (\Exception $e) {
                    echo "已存在<br />";
                }
            }
        }
    }


    public function actionRole()
    {
        $auth = wanhunet::$app->authManager;
        try {
            $admin = $auth->createRole("admin");
            $admin->description = '管理员';
            $auth->add($admin);
        } catch (\Exception $e) {
            $admin = $auth->getRole('admin');
        }

        foreach ($this->auth_child as $child) {
            foreach ($child as $key => $vo) {
                try {
                    $auth->addChild($admin, $auth->getPermission($key));
                } catch (\Exception $e) {
                    echo "已存在<br />";
                }
            }
        }
        try {
            $auth->assign($admin, wanhunet::$app->user->getId());
        } catch (\Exception $e) {
            echo "已存在<br />";
        }
    }
}