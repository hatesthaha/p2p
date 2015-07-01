<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\invest\controllers;


use modules\asset\models\Asset;
use modules\asset\models\AssetMoney;
use modules\invest\models\Invest;
use modules\invest\models\InvestList;
use modules\invest\models\InvestMonth;
use modules\member\models\Member;
use wanhunet\helpers\Utils;
use wanhunet\wanhunet;
use yii\db\QueryBuilder;

class ListController extends BackendController
{
    public function actionIndex()
    {
        return $this->getList();
    }

    public function actionView()
    {
        $investList = InvestList::find()
            ->where([InvestList::tableName() . '.id' => wanhunet::$app->request->post('id')])
            ->joinWith(['member' => function ($query) {
                /** @var \yii\db\ActiveQuery $query */
                $query->select(Member::$SELECT_ROW);
            }, 'invest'])->asArray()->one();
        $this->makeView($investList);
        return $investList;
    }

    public function actionTiyanIndex()
    {
        $investLists = InvestList::find()->joinWith(['member' => function ($query) {
            /** @var \yii\db\ActiveQuery $query */
            $query->select(Member::$SELECT_ROW);
        }, 'invest' => function ($q) {
            /** @var \yii\db\ActiveQuery $q */
            $q->where(['type' => Invest::TYPE_EXPERIENCE_MONEY])->orWhere(['type' => Invest::TYPE_REG]);
        }])
            ->andWhere(['<=', InvestList::tableName() . '.interest_time', time()])
            ->andWhere([
                InvestList::tableName() . '.interest_status' => InvestList::STATUS_ORDER_MADE,
                InvestList::tableName() . '.status' => InvestList::STATUS_PAYED
            ])
            ->orderBy('id desc')
            ->asArray()->all();

        foreach ($investLists as &$investList) {
            $this->makeView($investList);
        }

        return $investLists;
    }

    public function actionReturnRate()
    {
        $id = wanhunet::$app->request->post('id');
        /** @var InvestList[] $investLists */
        $investLists = InvestList::find()->joinWith(['member' => function ($query) {
            /** @var \yii\db\ActiveQuery $query */
            $query->select(Member::$SELECT_ROW);
        }, 'invest' => function ($q) {
            /** @var \yii\db\ActiveQuery $q */
            $q->where(['type' => Invest::TYPE_EXPERIENCE_MONEY])->orWhere(['type' => Invest::TYPE_REG]);
        }])
            ->andWhere(['<=', InvestList::tableName() . '.interest_time', time()])
            ->andWhere([
                InvestList::tableName() . '.id' => $id,
                InvestList::tableName() . '.interest_status' => InvestList::STATUS_ORDER_MADE,
                InvestList::tableName() . '.status' => InvestList::STATUS_PAYED
            ])
            ->orderBy('id desc')->all();

        $connection = wanhunet::$app->db;

        foreach ($investLists as $investList) {
            $memberId = $investList->member->id;
            $connection->createCommand()->insert(AssetMoney::tableName(), [
                'user_id' => $memberId,
                'step' => $investList->interest,
                'status' => AssetMoney::STATUS_INC,
                'type' => AssetMoney::TYPE_MONEY,
                'action' => wanhunet::$app->controller->getRoute(),
                'llinfo' => json_encode([mb_substr($investList['invest']['title'], 0, 5, 'utf-8') . '返息']),
                'action_uid' => $memberId,
                'created_at' => time(),
                'updated_at' => time()
            ])->execute();
            $asset = Asset::find()->where(['user_id' => $memberId])->asArray()->one();

            if ($investList->invest->type == Invest::TYPE_EXPERIENCE_MONEY) {
                $connection->createCommand()->update(Asset::tableName(), [
                    'money' => $asset['money'] + $investList->interest
                ], ['user_id' => $memberId])->execute();
            }

            $investList->status = InvestList::STATUS_ORDER_TRIAL;
            $investList->save();
        }

    }

    public function actionReturnRateMonth()
    {
        $id = wanhunet::$app->request->post('id');
        /** @var InvestMonth[] $investMonths */
        $investMonths = InvestMonth::find()->joinWith(['investList' => function ($query) {
            /** @var \yii\db\ActiveQuery $query */
            $query->joinWith(['member' => function ($query) {
                /** @var \yii\db\ActiveQuery $query */
                $query->select(Member::$SELECT_ROW);
            }, 'invest']);
        }])->orderBy('id desc')
            ->where(['m_status' => InvestMonth::STATUS_ACTIVE])
            ->andWhere([InvestMonth::tableName() . '.id' => $id])
            ->andWhere(['<=', 'm_time', time()])
            ->asArray()->all();
        $members = [];
        $assetMoneys = [];
        $ims = [];
        $investOver = [];
        foreach ($investMonths as $investMonth) {
            $memberId = $investMonth['investList']['member']['id'];
            if (isset($members[$memberId])) {
                $memberAsset = &$members[$memberId];
            } else {
                $asset = $asset = Asset::find()->where(['user_id' => $memberId])->asArray()->one();
                $members[$memberId] = $asset['money'];
                $memberAsset = &$members[$memberId];
            }
            if (InvestMonth::find()->where(['invest_list_id' => $investMonth['invest_list_id'], 'm_status' => InvestMonth::STATUS_ACTIVE])->count() == 1) {
                $investOver[] = $investMonth['investList']['id'];
                $step = $investMonth['investList']['investment_sum'] + $investMonth['m_step'];
            } else {
                $step = $investMonth['m_step'];
            }
            $memberAsset += $step;
            $assetMoneys[] = [
                $memberId,
                $step,
                AssetMoney::STATUS_INC,
                AssetMoney::TYPE_MONEY,
                wanhunet::$app->controller->getRoute(),
                $memberId,
                json_encode([mb_substr($investMonth['investList']['invest']['title'], 0, 5, 'utf-8') . '第' . $investMonth['m_date'] . '个月的返息']),
                time(),
                time()
            ];
            $ims[$investMonth['id']] = [
                'm_status' => InvestMonth::STATUS_DELETED
            ];
        }
        $batchInsertRows = ['user_id', 'step', 'status', 'type', 'action', 'action_uid', 'llinfo', 'created_at', 'updated_at'];
        $connection = wanhunet::$app->db;
        foreach ($members as $user_id => $amemberAsset) {
            $connection->createCommand()->update(Asset::tableName(), [
                'money' => $amemberAsset
            ], ['user_id' => $user_id])->execute();
        }
        foreach ($ims as $im_id => $im) {
            $connection->createCommand()->update(InvestMonth::tableName(), $im, ['id' => $im_id])->execute();
        }
        foreach ($investOver as $in) {
            $connection->createCommand()->update(InvestList::tableName(), [
                'status' => InvestList::STATUS_ORDER_TRIAL
            ], ['id' => $in])->execute();
        }
        if (count($assetMoneys) > 0) {
            $connection->createCommand()->batchInsert(AssetMoney::tableName(), $batchInsertRows, $assetMoneys)->execute();
        }


    }

    public function actionMonthIndex()
    {
        $investMonths = InvestMonth::find()->joinWith(['investList' => function ($query) {
            /** @var \yii\db\ActiveQuery $query */
            $query->joinWith(['member' => function ($query) {
                /** @var \yii\db\ActiveQuery $query */
                $query->select(Member::$SELECT_ROW);
            }, 'invest']);
        }])->orderBy('id desc')
            ->where(['m_status' => InvestMonth::STATUS_ACTIVE])->andWhere(['<=', 'm_time', time()])
            ->asArray()->all();
        foreach ($investMonths as &$investMonth) {
            $investMonth['m_time'] = date('Y-m-d H:i:s', $investMonth['m_time']);
            $investMonth['created_at'] = date('Y-m-d H:i:s', $investMonth['created_at']);
            $investMonth['m_status'] = InvestMonth::get_record_status($investMonth['m_status']);
            if (isset($investMonth['investList'])) {
                $this->makeView($investMonth['investList']);
                foreach ($investMonth['investList'] as $key => &$i) {
                    $investMonth['invest_list_' . $key] = $i;
                }
                unset($investMonth['investList']);
            }
        }
        return $investMonths;
    }

    private function getList($startTime = 0, $endTime = 0)
    {
        $endTime = $endTime == 0 ? time() : $endTime;
        $investLists = InvestList::find()->joinWith(['member' => function ($query) {
            /** @var \yii\db\ActiveQuery $query */
            $query->select(Member::$SELECT_ROW);
        }, 'invest'])
            ->andWhere(['>=', InvestList::tableName() . '.created_at', $startTime])
            ->andWhere(['<=', InvestList::tableName() . '.created_at', $endTime])
            ->orderBy('id desc')
            ->asArray()->all();

        foreach ($investLists as &$investList) {
            $this->makeView($investList);
        }

        return $investLists;

    }

    private function makeView(&$investList)
    {
        $investList['created_at'] = Utils::dateFormat('Y-m-d H:i:s', $investList['created_at']);
        $investList['updated_at'] = Utils::dateFormat('Y-m-d H:i:s', $investList['updated_at']);
        $investList['interest_time'] = Utils::dateFormat('Y-m-d H:i:s', $investList['interest_time']);
        $investList['pay_time'] = Utils::dateFormat('Y-m-d H:i:s', $investList['pay_time']);
        $investList['status'] = InvestList::get_status($investList['status']);
        $investList['interest_status'] = InvestList::get_status($investList['interest_status']);
        if ($investList['status'] == '终审通过') {
            $investList['interest_status'] = '已返息';
        }
        if (isset($investList['member'])) {
            $investList['member']['idcard_status'] = Member::get_auth_status($investList['member']['idcard_status']);
            $investList['member']['email_status'] = Member::get_auth_status($investList['member']['email_status']);
            $investList['member']['status'] = Member::get_record_status($investList['member']['status']);
            $investList['member']['created_at'] = Utils::dateFormat('Y-m-d', $investList['member']['created_at']);

            foreach ($investList['member'] as $key => &$memberInfo) {
                $investList['member_' . $key] = $memberInfo;
            }
            unset($investList['member']);
        }

        if (isset($investList['invest'])) {
            $investList['invest']['invest_status'] = InvestList::get_record_status($investList['invest']['invest_status']);
            $investList['invest']['created_at'] = Utils::dateFormat('Y-m-d', $investList['invest']['created_at']);
            $investList['invest']['updated_at'] = Utils::dateFormat('Y-m-d', $investList['invest']['updated_at']);
            $investList['invest']['buy_time_start'] = Utils::dateFormat('Y-m-d', $investList['invest']['buy_time_start']);
            $investList['invest']['buy_time_end'] = Utils::dateFormat('Y-m-d', $investList['invest']['buy_time_end']);
            $investList['invest']['type'] = Invest::get_type($investList['invest']['type']);

            foreach ($investList['invest'] as $key => &$investInfo) {
                $investList['invest_' . $key] = $investInfo;
            }
            unset($investList['invest']);
        }
    }
}