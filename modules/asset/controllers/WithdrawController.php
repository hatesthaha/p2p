<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\asset\controllers;


use common\models\Config;
use modules\asset\models\AssetMoney;
use modules\member\models\Member;
use wanhunet\wanhunet;
use yii\helpers\ArrayHelper;

class WithdrawController extends BackendController
{
    public function actionIndex()
    {
        return $this->rechargeList();
    }

    public function actionCheck()
    {

    }

    public function actionFirstTrial()
    {
        $request = wanhunet::$app->request;
        if ($request->isGet) {
            $startTime = strtotime($request->get('start_time'));
            $endTime = strtotime($request->get('end_time'));
            return $this->rechargeList($startTime, $endTime, [
                'wh_asset_money.status' => AssetMoney::STATUS_ORDER_MADE
            ]);
        } else {
            return AssetMoney::updateAll(['status' => AssetMoney::STATUS_ORDER_FIRST_TRIAL], ['id' => $request->post("id")]);
        }
    }

    public function actionFinalTrial()
    {
        $request = wanhunet::$app->request;
        if ($request->isGet) {
            $startTime = strtotime($request->get('start_time'));
            $endTime = strtotime($request->get('end_time'));
            return $this->rechargeList($startTime, $endTime, [
                'wh_asset_money.status' => AssetMoney::STATUS_ORDER_FIRST_TRIAL
            ]);
        } else {
            return AssetMoney::updateAll(['status' => AssetMoney::STATUS_ORDER_TRIAL], ['id' => $request->post("id")]);
        }
    }

    private function rechargeList($startTime = 0, $endTime = 0, $where = [])
    {
        $endTime = $endTime == 0 ? time() : $endTime;
        $assetMoneys = AssetMoney::find()->joinWith(['member' => function ($query) {
            /** @var \yii\db\ActiveQuery $query */
            $query->select(Member::$SELECT_ROW);
        }])->andWhere(ArrayHelper::merge(['type' => AssetMoney::TYPE_MENTION], $where))
            ->andWhere(['>=', 'wh_asset_money.created_at', $startTime])
            ->andWhere(['<=', 'wh_asset_money.created_at', $endTime])
            ->orderBy('id desc')
            ->asArray()
            ->all();

        $actions = Config::getInstance()->getProperty('assetLog');

        foreach ($assetMoneys as &$assetMoney) {
            $assetMoney['action'] = isset($actions[$assetMoney['action']]) ? $actions[$assetMoney['action']] : "赠送";
            $assetMoney['created_at'] = date('Y-m-d', $assetMoney['created_at']);
            $assetMoney['updated_at'] = date('Y-m-d', $assetMoney['updated_at']);
            $assetMoney['type'] = AssetMoney::get_type($assetMoney['type']);
            $assetMoney['status'] = AssetMoney::get_status($assetMoney['status']);
            if (isset($assetMoney['member'])) {
                $assetMoney['member']['idcard_status'] = Member::get_auth_status($assetMoney['member']['idcard_status']);
                $assetMoney['member']['email_status'] = Member::get_auth_status($assetMoney['member']['email_status']);
                $assetMoney['member']['status'] = Member::get_record_status($assetMoney['member']['status']);
                $assetMoney['member']['created_at'] = date('Y-m-d', $assetMoney['member']['created_at']);

                foreach ($assetMoney['member'] as $key => &$memberInfo) {
                    $assetMoney['member_' . $key] = $memberInfo;
                }
                unset($assetMoney['member']);
            }
        }
        return $assetMoneys;
    }
}