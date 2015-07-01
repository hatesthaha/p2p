<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\member\controllers;


use modules\asset\models\Asset;
use modules\asset\models\AssetMoney;
use modules\asset\models\BankCard;
use modules\member\models\Member;
use modules\member\models\MemberOther;
use wanhunet\wanhunet;
use yii\db\ActiveQuery;
use yii\db\Query;

class MembersController extends BackendController
{
    public function actionIndex()
    {
        $query = (new Query())
            ->select([
                '`wh_member`.`id`',
                '`wh_member`.`username`',
                '`wh_member`.`phone`',
                '`wh_member`.`status`',
                '`wh_member`.`created_at`',
                '`wh_asset`.`money`'
            ])
            ->where("`user_id`=`id`")
            ->andWhere(['status' => Member::STATUS_ACTIVE])
            ->from('`wh_member`, `wh_asset`')
            ->all();
        foreach ($query as $k => $v) {
            $query[$k]['created_at'] = date("Y-m-d ", $v['created_at']);
        }
        return $query;
    }

    public function actionView()
    {

        $id = wanhunet::$app->request->post('id');

        /** @var Member $member */
        $memberCow = Member::$SELECT_ROW;
        $member = Member::find()
            ->where(['id' => $id])
            ->select($memberCow)
            ->asArray()
            ->one();
        $parentMember = Member::find()
            ->select($memberCow)
            ->where(['invitation' => $member['parent_id']])->asArray()
            ->one();

        $asset = Asset::find()
            ->where(['user_id' => $id])
            ->select(
                [
                    'experience_money_max', 'experience_money_max_inc', 'money', 'bank_card'
                ]
            )->asArray()
            ->one();
        if (!empty($asset['bank_card'])) {
            $bankCards = empty($asset['bank_card']) ? [] : json_decode($asset['bank_card']);
            foreach ($bankCards as $bankCard) {
                $bankCard = unserialize($bankCard);
                if ($bankCard instanceof BankCard) {
                    /* @var \modules\asset\models\BankCard $bankcard */
                    $asset['bank_card'] = [
                        'cardId' => $bankCard->cardId,
                        'backName' => $bankCard->bankName,
                        'cardUserName' => $bankCard->cardUserName,
                        'cardPhone' => $bankCard->cardPhone
                    ];
                }
            }
        }

        $asset['experience_money'] = AssetMoney::getExperienceMoney($id);
        /** @var MemberOther[] $memberOther */
        $memberOther = MemberOther::find()->where(['user_id' => $id])->all();
        foreach ($memberOther as $info) {
            if ($info->table == MemberOther::TABLE_JIUXIN) {
                $info->table = '玖信贷';
            } elseif ($info->table == MemberOther::TABLE_WECHAT) {
                $info->table = '微信账号';
            }
        }
        $friends = Member::findOne($id)->findFriends();

        return [
            'member' => $member,
            'parent' => $parentMember,
            'asset' => $asset,
            'MemberOther' => $memberOther,
            'friends' => $friends
        ];


    }

    public function actionBlacklist()
    {
        return Member::find()
            ->where(['status' => Member::STATUS_DELETED])
            ->orderBy('id desc')
            ->select(['id', 'username', 'phone', 'status', 'created_at'])
            ->all();
    }

    public function actionLock()
    {
        $request = wanhunet::$app->request;
        return Member::updateAll(['status' => Member::STATUS_DELETED], ['id' => $request->post("id")]);

    }

    public function actionUnlock()
    {
        $request = wanhunet::$app->request;
        return Member::updateAll(['status' => Member::STATUS_ACTIVE], ['id' => $request->post("id")]);
    }
}