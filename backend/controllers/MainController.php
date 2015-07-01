<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace backend\controllers;


class MainController extends BackendController
{
    public function behaviors()
    {
        return [];
    }

    public function actionIndex()
    {
        return "nihao";
    }

}