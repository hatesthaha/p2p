<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace wanhunet\base;


use wanhunet\wanhunet;

class View extends \yii\web\View
{
    public function init()
    {
        parent::init();

        if (wanhunet::$app->getSession()->hasFlash('errors')) {
            $this->params['errors'] = wanhunet::$app->getSession()->getFlash('errors');
        }
    }

    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
    }

    public function getParam($name, $default = null)
    {
        return isset($this->params[$name]) ? $this->params[$name] : $default;
    }

    public function getParamsErrors($name, $default = null)
    {
        return isset($this->params['errors'][$name]) ? $this->params['errors'][$name] : $default;
    }

    public function setParamsErrors($name, $value)
    {
        $this->params['errors'][$name] = $value;
    }
}