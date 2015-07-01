<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace frontend\controllers;

use common\models\Config;
use modules\asset\models\AssetMoney;
use modules\asset\models\ShareGift;
use modules\invest\models\Invest;
use modules\invest\models\InvestList;
use modules\member\models\Member;
use modules\member\models\MemberOther;
use wanhunet\wanhunet;
use yii\base\Event;
use yii\helpers\Url;

class ShareController extends FrontendController
{
    public function behaviors()
    {
        return [];
    }

    public function actionIndex($invitation = null)
    {
        if ($invitation !== null)
            $_GET['parent'] = $invitation;

        $request = wanhunet::$app->request;
        $wechat = wanhunet::app()->wechat;
        $token = $wechat->getOauth2AccessToken($request->get('code'));
        $refreshToken = $wechat->refreshOauth2AccessToken($token['refresh_token']);
        $info = $wechat->getSnsMemberInfo($refreshToken['openid'], $refreshToken['access_token']);
        if (is_bool($info)) {
            return "请在微信中打开";
        }
        /** @var ShareGift $shareGift */
        if (($shareGift = ShareGift::find()->where(['wechat_id' => $refreshToken['openid']])->one()) === null) {
            $shareGift = new ShareGift();
            $shareGift->wechat_info = json_encode($info);
            $shareGift->wechat_id = $refreshToken['openid'];
            $giftList = explode(',', Config::getInstance()->getProperty("setIncEM.gift"));
            $shareGift->gift_limit = $giftList[rand(0, count($giftList) - 1)];
            if (!is_numeric($shareGift->gift_limit)) {
                return "请在微信中打开";
            }
            $shareGift->save();
        } else {
            if ($shareGift->has_gift == ShareGift::HAS_GIFT) {
                return $this->hasGift($shareGift);
            }
        }
        /** @var Invest $invest */
        $invest = Invest::find()->where(["type" => Invest::TYPE_REG])->one();
        $num = ($shareGift->gift_limit * $invest->invest_date * ($invest->rate / 100)) / 12;
        $num = sprintf("%.2f", $num);
        return $this->view("index", [
            'shareGift' => $shareGift,
            'num' => $num,
            'wechat_id' => $info['openid']
        ]);
    }

    public function actionShare()
    {
        return $this->view("has_gift");
    }

    private function hasGift(ShareGift $shareGift)
    {
        $wechat_info = json_decode($shareGift->wechat_info, true);

        $emAction = ['site/idcard', 'site/email', 'site/setjiuxin', 'site/recharge'];
        $emCount = AssetMoney::find()->where(['user_id' => $shareGift->user_id, "action" => $emAction])->count();
        $emshareCount = AssetMoney::find()
            ->where(['user_id' => $shareGift->user_id, 'action' => 'site/signup-verify'])
            ->andWhere(['<>', "action_uid", $shareGift->user_id])
            ->count();
        if ($emshareCount > 0) {
            $emCount += 1;
        }
        $emCount = 5 - $emCount;
        $members = [];
        if (($parent_id = Member::findOne($shareGift->user_id)->parent_id) !== null) {
            $members = Member::find()
                ->where(['parent_id' => $parent_id])
                ->asArray()
                ->select(['id', 'created_at'])
                ->limit(10)
                ->orderBy("id desc")
                ->all();
        }

        $url = wanhunet::$app->urlManager->createAbsoluteUrl(['share/index/' . Member::findOne($shareGift->user_id)->invitation]);
        $url = wanhunet::app()->wechat->getOauth2AuthorizeUrl($url, 'authorize', 'snsapi_userinfo');

        return $this->view("has_gift", [
            'wechat_info' => $wechat_info,
            'emCount' => $emCount,
            'members' => $members,
            'url' => $url
        ]);
    }

    public function actionSignupFinish()
    {
        try {
            $member = wanhunet::app()->member;
            $wechat_id = $member->getOtherInfo(MemberOther::TABLE_WECHAT);
            /** @var ShareGift $shateGift */
            $shateGift = ShareGift::find()->where(['wechat_id' => $wechat_id->row])->one();
            $limit = $shateGift->gift_limit;
            /** @var Invest $invest */
            $invest = Invest::find()->where(["type" => Invest::TYPE_REG])->one();

            if (InvestList::hasBuy($member->getId(), $invest->id)) {
                $investOrder = InvestList::find()
                    ->where(["member_id" => $member->getId()])
                    ->andWhere(['invest_id' => $invest->id])
                    ->andWhere(['status' => self::STATUS_PAYED])
                    ->one();
            } else {
                $investOrder = new InvestList();
                $investOrder->investment_sum = $limit;
                $investOrder->invest_id = $invest->id;
                $order = $invest->markOrderReg($investOrder);
                $investOrder->finishPay(new Event());
                $shateGift->has_gift = ShareGift::HAS_GIFT;
                $shateGift->user_id = $member->id;
                $shateGift->save();
            }
            return $this->view("signup_finish", [
                'info' => $investOrder
            ]);
        } catch (\Exception $e) {
//            throw $e;
            return $this->redirect(Url::to(['site/signin']));
        }
    }
}