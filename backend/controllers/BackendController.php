<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace backend\controllers;


use wanhunet\helpers\MenuNav;
use wanhunet\wanhunet;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

/**
 * 用户公用后台控制器，后台操作控制器请继承此类
 * 当子类重写behaviors()时，请使用 [[\yii\helpers\ArrayHelper::merge()]]进行合并
 * Class BackendController
 * @package backend\controllers
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class BackendController extends \yii\rest\Controller
{
    /**
     * 当前用户的所有权限
     * @var array
     */
    protected $_permissions = [];
    public $menuNav = [];
    protected $allNav = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behavior = [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [wanhunet::$app->controller->getRoute()],
                    ]
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new ForbiddenHttpException();
                }
            ],
        ];

        return ArrayHelper::merge($behavior, parent::behaviors());

    }

    public function init()
    {
        parent::init();

        $request = wanhunet::$app->request;
        if (!$request->isGet) {
            $params = json_decode(file_get_contents('php://input'), true);
            $request->setBodyParams($params);
        }
    }


}