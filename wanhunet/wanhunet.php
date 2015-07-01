<?php

namespace wanhunet;


/**
 * Class wanhunet
 * \Yii 的别名 定义 [[\wanhunet\wanhunet::app|app()]] 用于IDE友好提示 可以添加第三方组件时友好提示
 * @package wanhunet
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 */
class wanhunet extends \Yii
{
    /**
     * 返回 parent::$app 用于 IDE 第三方组件提示
     * 建议使用第三方组件时使用 [[\wanhunet\wanhunet::app|app()]] 进行调用 便于区分使用
     * 在\wanhunet\base\Application中
     * 使用 '@property $propertyName' 的方式进行第三方组件的注册提示
     * @return \wanhunet\base\Application
     */
    public static function app(){
        return parent::$app;
    }

}