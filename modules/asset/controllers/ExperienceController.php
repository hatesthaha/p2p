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

class ExperienceController extends BackendController
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
        }])->where(['type' => AssetMoney::TYPE_EXPERIENCE_MONEY])
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

    /**
     * @return array
     * @throws \yii\db\Exception
     *
     * verify_success_email        //验证邮箱成功
     * verify_success_phone        //验证手机成功
     * firstMoney                  //第一次充值
     * firstIdcard                 //填写身份证
     * jiuxin                      //绑定玖信账号
     * setIncEM.wechat                      /绑定玖信账号
     * setIncEM.invitationParent            //用户通过邀请码注册父级得分
     * setIncEM.invitationParentConut9      //用户通过邀请码第九个注册父级得分
     * setIncEM.invitationConut9            //用户通过邀请码第九个注册自己得分
     */
    public function actionSetting()
    {
        $request = wanhunet::$app->request;
        if ($request->isGet) {
            $configs = Config::getInstance()->config;
            foreach ($configs as $key => &$config) {
                if (strstr($key, 'setIncEM.')) {
                    $configs[str_replace('setIncEM.', '', $key)] = $config;
                    unset($configs[$key]);
                }
            }
            return json_encode($configs);
        } else {
            $connection = wanhunet::$app->db;
            $rs = [];
            foreach ($request->post() as $key => $value) {
                try{
                    $rs[] = $connection->createCommand()->update('wh_config', ['value' => $value], ['key' => 'setIncEM.' . $key])->execute();
                }catch (\Exception $e){}
            }
            return $rs;
        }
    }


    /**
     * 用户名 username input
     * 金额   step     input_int
     * 金额状态 status  radio     选项：：增长：20; 减少：30
     *
     * @return string|bool
     */
    public function actionNewEm()
    {
        $request = wanhunet::$app->request;
        $memberModel = Member::findByUsername($request->post('username'));
        $status = [AssetMoney::STATUS_INC, AssetMoney::STATUS_DEC];
        if ($memberModel !== null) {
            $assetMoneyModel = new AssetMoney();
            $assetMoneyModel->action = '';
            $assetMoneyModel->user_id = $memberModel->id;
            $assetMoneyModel->step = $request->post('step');
            $assetMoneyModel->status = in_array($request->post('status'), $status) ? $request->post('status') : 0;
            $assetMoneyModel->type = AssetMoney::TYPE_EXPERIENCE_MONEY;
            $assetMoneyModel->action_uid = $memberModel->id;
            if ($assetMoneyModel->save()) {
                return true;
            } else {
                return current($assetMoneyModel->getFirstErrors());
            }
        } else {
            return '用户不存在';
        }


    }
}