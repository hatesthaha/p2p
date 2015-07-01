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
use wanhunet\helpers\Utils;
use wanhunet\wanhunet;

class RechargeController extends BackendController
{
    public function actionExport()
    {
        $request = wanhunet::$app->request;
        $startTime = strtotime($request->get('start_time'));
        $endTime = strtotime($request->get('end_time'));
        $asset = $this->rechargeList($startTime, $endTime);
        Utils::exportExcel($asset,
            [
                'ID', '会员ID', '金额', '金额状态', '类型', '动作',
                '动作用户ID', '', '创建时间', '更新时间', '会员ID', '会员用户名',
                '用户手机号', '用户状态', '用户创建时间', '用户身份证号', '身份证名称'
                , '用户身份证状态', '用户邮箱', '用户邮箱状态', '父级邀请码', '邀请码'
            ], '充值记录导出' . date('Y.m.d')
        );
    }

    public function actionIndex()
    {
        return $this->rechargeList();
    }

    private function rechargeList($startTime = 0, $endTime = 0)
    {
        $endTime = $endTime == 0 ? time() : $endTime;
        $assetMoneys = AssetMoney::find()->joinWith(['member' => function ($query) {
            /** @var \yii\db\ActiveQuery $query */
            $query->select(Member::$SELECT_ROW);
        }])->where(['type' => AssetMoney::TYPE_MONEY])
            ->andWhere(['>=', 'wh_asset_money.created_at', $startTime])
            ->andWhere(['<=', 'wh_asset_money.created_at', $endTime])
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