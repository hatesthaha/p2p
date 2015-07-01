<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace frontend\controllers;


use common\models\Debug;
use modules\asset\models\AssetMoney;
use modules\invest\models\InvestList;
use wanhunet\base\Controller;
use wanhunet\components\llPayComponent;
use wanhunet\components\PayEvent;
use wanhunet\helpers\ErrorCode;
use wanhunet\wanhunet;
use yii\base\ErrorException;
use yii\helpers\Url;

class PayController extends FrontendController
{
    public $layout = 'layout';
    public function actionIndex()
    {
        return $this->view('index');
    }

    public function actionNotify()
    {
        $pay = wanhunet::app()->pay;
        $pay->on(llPayComponent::EVENT_SUCCESS_NOTIFY, function ($event) {
            /** @var \wanhunet\components\PayEvent $event */
            $data = $event->rs;
            $investList = InvestList::findByIdNo($data['no_order']);
            if ($investList !== null) {
                $investList->finishPay($event);
            }
        });
        $pay->on(llPayComponent::EVENT_FAIL_NOTIFY, function ($event) {

        });
        $pay->notifyReturn();
    }

    public function actionReturn()
    {
        $sucesstitle = '操作成功！';
        return $this->view('pay_return', ['sucesstitle' => $sucesstitle]);
    }

    public function actionPayNotify()
    {
        $pay = wanhunet::app()->pay;
        $pay->on(llPayComponent::EVENT_SUCCESS_NOTIFY, function ($event) {
            /** @var \wanhunet\components\PayEvent $event */
            $data = $event->rs;
            $money = AssetMoney::findByIdNo($data['no_order']);
            if ($money !== null) {
                $money->finishPay($event);
            }

        });
        $pay->on(llPayComponent::EVENT_FAIL_NOTIFY, function ($event) {
            /** @var \wanhunet\components\PayEvent $event */
            $data = $event->data;
        });

        $pay->notifyReturn();
    }

    public $enableCsrfValidation = false;

    /**
     * array(1) {
     *      ["res_data"]  => string(243) "{
     *               "dt_order":"20150324105400",
     *               "money_order":"0.01",
     *               "no_order":"129",
     *               "oid_partner":"201408071000001543",
     *               "oid_paybill":"2015032439499046",
     *               "result_pay":"SUCCESS",
     *               "settle_date":"20150324",
     *               "sign":"53d06077ab8ae50eaf86ab05e5613956",
     *               "sign_type":"MD5"
     *         }"
     * }
     */
    public function actionPayReturn()
    {
        $res_data = wanhunet::$app->request->post('res_data');
        $res_data = json_decode($res_data, true);
        if (count($res_data) <= 0) {
            return $this->redirect(Url::to(['site/setup']));
        }
        $sucesstitle = '充值' . $res_data['money_order'] . '元！';
        return $this->view('pay_return', ['sucesstitle' => $sucesstitle]);
    }

    public function actionPayPass()
    {
        $order = \wanhunet\wanhunet::$app->getSession()->get('order');
        if ($order == null) {
            return $this->goBack(['info' => "订单错误"], Url::to(['site/main']));
        }
        return $this->view('pay_pass');
    }

    public function actionPayWithBalance()
    {
        try {
            $pass = wanhunet::app()->member->validatePayPass(wanhunet::$app->request->post('paypass'));
            if (!$pass) {
                throw new ErrorException('密码验证错误', ErrorCode::Pay_pass_empty);
            }
            /** @var InvestList $order */
            $order = InvestList::findOne(wanhunet::$app->getSession()->get("order"));
            wanhunet::app()->member->payOrderWithBalance($order);
            wanhunet::$app->getSession()->remove('order');
            return $this->view('/invest/pay_return');
        } catch (\ErrorException $e) {
            if ($e->getCode() == ErrorCode::Pay_pass_empty) {
                return $this->goBack([
                    'info' => $e->getMessage()
                ], Url::to(['pay/pay-pass']));
            } else {
                return $this->goBack([
                    'info' => $e->getMessage()
                ], Url::to(['pay/pay-pass']));
            }
        }
    }

    public function actionMention()
    {
        try {
            $pass = wanhunet::app()->member->validatePayPass(wanhunet::$app->request->post('paypass'));
            if (!$pass) {
                throw new ErrorException('密码验证错误', ErrorCode::Pay_pass_empty);
            }
            /** @var AssetMoney $order */
            $order = AssetMoney::findOne(wanhunet::$app->getSession()->get("order")[1]);

            $member = wanhunet::app()->member;
            $member->setDecMoney($order->step);
            $member->saveMoney();


            $order->status = AssetMoney::STATUS_ORDER_MADE;
            $order->save();

            wanhunet::$app->getSession()->remove('order');
            return $this->view('mention');
        } catch (\Exception $e) {
            if ($e->getCode() == ErrorCode::Pay_pass_empty) {
                return $this->goBack([
                    'info' => $e->getMessage()
                ], Url::to(['pay/pay-pass']));
            } elseif ($e->getCode() == 0) {
                return $this->goBack([
                    'info' => "请输入密码"
                ], Url::to(['pay/pay-pass']));
            } else {
                return $this->goBack([
                    'info' => $e->getMessage()
                ], Url::to(['pay/pay-pass']));
            }
        }
    }

}