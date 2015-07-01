<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace frontend\controllers;


use modules\asset\models\AssetMoney;
use modules\invest\models\Invest;
use modules\invest\models\InvestList;
use wanhunet\helpers\ErrorCode;
use wanhunet\wanhunet;
use yii\helpers\Url;

class InvestController extends FrontendController
{
    public function actionView()
    {
        $id = wanhunet::$app->request->get("id");
        if (($invest = Invest::findOne($id)) !== null) {
            /** @var Invest $invest */
            $orderSum = InvestList::getAlreadyBuy($invest);
            $invest = $invest->toArray();
            $invest['orderSum'] = $orderSum;
            return $this->view("view", [
                'invest' => $invest
            ]);
        } else {
            return $this->goBack([
                'info' => '标不存在'
            ], Url::to(['invest/list']));
        }
    }

    public function actionProfit()
    {
        $user = wanhunet::$app->user;
        $assetMoneyList = AssetMoney::find()
            ->where(['type' => AssetMoney::TYPE_MONEY])
            ->andWhere(['<>', 'status', AssetMoney::STATUS_MONEY_UNPAYED])
            ->andWhere(['user_id' => $user->getId()])
            ->limit(10)
            ->orderBy('id desc')
            ->all();
        $investList = InvestList::find()
            ->where(['member_id' => $user->getId(), 'status' => InvestList::STATUS_PAYED])
            ->joinWith([
                'invest' => function ($query) {
                    /** @var \yii\db\ActiveQuery $query */
                    $query->andWhere(['type' => Invest::TYPE_MONEY]);
                }
            ])
            ->limit(10)
            ->orderBy('id desc')
            ->all();
        return $this->view('profit', [
            'assetMoneyList' => $assetMoneyList,
            'investList' => $investList
        ]);
    }

    public function actionList()
    {
        $invest = Invest::find()
            ->where(['invest_status' => Invest::STATUS_ACTIVE])
            ->andWhere(['>=', 'buy_time_end', time()])
            ->andWhere(['<=', 'buy_time_start', time()])
            ->limit(10)
            ->orderBy('id desc');
        $experiences = clone $invest;
        $experiences = $experiences
            ->andWhere(['type' => Invest::TYPE_EXPERIENCE_MONEY])
            ->all();
        $moneys = clone $invest;
        $moneys = $moneys
            ->andWhere(['type' => Invest::TYPE_MONEY])
            ->all();
        return $this->view('list', [
            'experiences' => $experiences,
            'moneys' => $moneys
        ]);
    }

    public function actionPay()
    {
        $member = wanhunet::app()->member;
        $request = wanhunet::$app->request;
        $investOrder = new InvestList();
        /** @var Invest $invest */
        $invest = Invest::findOne($request->post('invest_id'));
        $investOrder->investment_sum = $request->post('investment_sum');
        $investOrder->invest_id = $request->post('invest_id');
        if (empty($investOrder->investment_sum) or empty($investOrder->invest_id)) {
            $urlr = wanhunet::$app->request->post('return_url', 'list');
            $url = ['invest/' . $urlr];
            if ($urlr === 'view') {
                $url = ['invest/' . $urlr, 'id' => $request->post('invest_id')];
            }
            return $this->goBack([
                "info" => "请填写所有内容"
            ], Url::to($url));
        }
        try {
            $order = $invest->markOrder($investOrder);
        } catch (\ErrorException $e) {
            $urlr = wanhunet::$app->request->post('return_url', 'list');
            $url = ['invest/' . $urlr];
            if ($urlr === 'view') {
                $url = ['invest/' . $urlr, 'id' => $request->post('invest_id')];
            }
            return $this->goBack([
                'info' => $e->getMessage()
            ], Url::to($url));
        }
        if ($request->post('pay_style') == 'balance') {
            try {
                /** @var InvestList $order */
                $order = InvestList::findOne($investOrder->id);
                return $member->payOrderWithBalance($order);
            } catch (\ErrorException $e) {
                if ($e->getCode() == ErrorCode::Pay_pass_empty) {
                    wanhunet::$app->getSession()->set('order', $investOrder->id);
                    return $this->redirect(Url::to(['pay/pay-pass']));
                } else {
                    return $this->goBack([
                        'info' => $e->getMessage()
                    ], Url::to(['invest/list']));
                }
            }
        } else {
            $bankCard = $member->getBankCard();
            if (count($bankCard) <= 0) {
                return $this->goBack([
                    'info' => '请先绑定银行卡'
                ], Url::to(['site/setup']));
            } else {
                $urlManager = wanhunet::$app->urlManager;
                $order->setUrl($urlManager->createAbsoluteUrl(['pay/return']), $urlManager->createAbsoluteUrl(['pay/notify']));
                die($member->payOrderWithBankCard($order, reset($bankCard)));
            }
        }
    }

    public function actionWithdraw()
    {
        return $this->view('withdraw');
    }

    public function actionMention()
    {
        $member = wanhunet::app()->member;
        $bankCard = $member->getBankCard();
        if (count($bankCard) > 0) {
            $bankCard = current($bankCard);
        } else {
            return $this->goBack([
                'info' => '请绑定银行卡'
            ], Url::to(['site/recharge']));
        }
        return $this->view('mention', [
            'bankCard' => $bankCard
        ]);
    }

    public function actionMentionPost()
    {
        $member = wanhunet::app()->member;
        $request = wanhunet::$app->request;
        $assetMoney = new AssetMoney();
        $assetMoney->step = $request->post('step');
        $assetMoney->status = AssetMoney::STATUS_MONEY_UNPAYED;
        $assetMoney->type = AssetMoney::TYPE_MENTION;
        $assetMoney->user_id = $member->getId();
        if ($assetMoney->saveOrder()) {
            wanhunet::$app->getSession()->set('order', ['asset', $assetMoney->id]);
            return $this->redirect(Url::to(['pay/pay-pass']));
        } else {
            return $this->goBack([
                'info' => '请输入提现金额'
            ], Url::to(['invest/mention']));
        }
    }

    public function actionSeeProfitm()
    {


    }

    public function actionSeeProfite()
    {
        $model = InvestList::find()->where(['user_id' => wanhunet::$app->user->getId()])
            ->joinWith([
                'invest' => function ($query) {
                    /** @var \yii\db\ActiveQuery $query */
                    $query->where(['type' => Invest::TYPE_EXPERIENCE_MONEY]);
                }
            ]);
//            ->sum('interest');
        $leiji = clone $model;
        $leiji = $leiji->andWhere(['<=', 'interest_time', time()])->sum('interest');

        $yesterday = clone $model;
        $yesterday = $yesterday->andWhere(['>=', 'interest_time', time()])->all();
        $yesterdayshou = 0;
        foreach ($yesterday as $y) {
            /** @var InvestList $y */
            $yesterdayshou += $y->getEarnings(1);
        }
        $time = time() - (10 * 3600 * 24);
        $list = [];

        $daysc = (3600 * 24);
        $lists = InvestList::find()->where(['user_id' => wanhunet::$app->user->getId()])->andWhere(['<=', 'interest_time', (time() - 11 * $daysc)]);


        $keshou = clone $model;
        $keshou = $keshou
            ->andWhere(['interest_status' => InvestList::STATUS_ORDER_MADE])
            ->andWhere(['<=', 'interest_time', time()])
            ->sum('interest');

        return $this->view('see-profite', [
            'leiji' => $leiji,
            'yesterdayshou' => $yesterdayshou,
            'list' => $list,
            'keshou' => $keshou
        ]);
    }

    public function actionFund()
    {


        return $this->view('fund');
    }

    public function actionOwnProfit()
    {
        $member = wanhunet::app()->member;
        $model = InvestList::find()
            ->where(['member_id' => $member->getId()])
            ->andWhere(['status' => InvestList::STATUS_PAYED])
            ->orWhere(['status' => InvestList::STATUS_ORDER_TRIAL])
            ->orderBy('id desc')->limit(50);

        $eModel = clone $model;
        $eModel = $eModel->joinWith([
            'invest' => function ($query) {
                /** @var \yii\db\ActiveQuery $query */
                $query->andWhere(['type' => Invest::TYPE_EXPERIENCE_MONEY])->orWhere(['type' => Invest::TYPE_REG]);
            }
        ])->all();

        $mModel = clone $model;
        $mModel = $mModel->joinWith([
            'invest' => function ($query) {
                /** @var \yii\db\ActiveQuery $query */
                $query->andWhere(['type' => Invest::TYPE_MONEY]);
            }
        ])->all();
        return $this->view('own_profit', [
            'eModel' => $eModel,
            'mModel' => $mModel,
            'member' => $member
        ]);
    }
}