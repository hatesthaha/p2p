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

class FinanceController extends BackendController
{
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
        }])->andWhere(['>=', 'wh_asset_money.created_at', $startTime])
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