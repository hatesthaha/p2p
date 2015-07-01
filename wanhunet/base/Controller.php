<?php


namespace wanhunet\base;

use wanhunet\wanhunet;
use yii\web\Response;


/**
 * 继承 \yii\web\Controller 用于丰富\yii\web\Controller 在控制器中请继承来自此类而非\yii\web\Controller
 * Class Controller
 * @package wanhunet\base
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 *
 */
class Controller extends \yii\web\Controller
{
    /**
     * 动态改变 Response 方式，如果是ajax则使用json返回，请使用此方法进行Html以及JOSN进行返回
     * @param $view
     * @param array $params
     * @return array|string
     */
    public function view($view, $params = [])
    {
        if (wanhunet::$app->request->isAjax) {
            wanhunet::$app->response->format = Response::FORMAT_JSON;
            return $params;
        } else {
            return $this->render($view, $params);
        }
    }


}