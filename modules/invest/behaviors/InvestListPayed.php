<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\invest\behaviors;


use common\models\Debug;
use modules\asset\models\Asset;
use modules\invest\models\Invest;
use modules\invest\models\InvestList;
use modules\invest\models\InvestMonth;
use wanhunet\base\Behavior;
use wanhunet\base\Order;
use wanhunet\helpers\Utils;
use wanhunet\wanhunet;
use yii\base\Event;

class InvestListPayed extends Behavior
{
    const FUNCTION_SET_INTEREST = 'setInterest';
    const FUNCTION_SET_INTEREST_M = 'setInterestm';

    public function events()
    {
        return Utils::eventMerge(self::className(), parent::events());
    }

    public function setInterest(Event $event)
    {
        /** @var InvestList $investList */
        $investList = $event->sender;
        $investList->interest = $this->_calculatedInterest($investList);
        $investList->interest_status = InvestList::STATUS_ORDER_MADE;
        $invest = $investList->getInvestModel();
        $invest_date = $invest->invest_date;
        $date = date('Y-m-d H:i:s ', $investList->pay_time);
        $investList->interest_time = strtotime($date . $invest_date . 'month');

        //体验金上限增长
        if ($investList->getType() == Invest::TYPE_MONEY) {
            $member = wanhunet::app()->member;
            $member->setIncExperienceMoneyInc($investList->investment_sum);
            if (($parent = $member->getParentModel()) !== null) {
                Asset::findOne($parent->id)->setIncExperienceMoneyInc($investList->investment_sum);
            }
        }

    }

    public function setInterestm(Event $event)
    {
        /** @var InvestList $investList */
        $investList = $event->sender;
        $investListId = $investList->id;
        $step = Utils::moneyFormat($investList->interest / $investList->invest->invest_date);
        $payTime = $investList->pay_time;
        if ($investList->invest->type == Invest::TYPE_MONEY) {
            for ($i = 1; $i <= $investList->invest->invest_date; $i++) {
                wanhunet::$app->db->createCommand()->insert(InvestMonth::tableName(), [
                    'invest_list_id' => $investListId,
                    'm_step' => $step,
                    'm_status' => Invest::STATUS_ACTIVE,
                    'm_time' => $payTime + (3600 * 24 * 30 * $i),
                    'm_date' => $i,
                    'created_at' => $payTime,
                    'updated_at' => $payTime
                ])->execute();
            }
        }
    }

    private function _calculatedInterest($investList)
    {
        $investRs = 0;
        if ($investList instanceof InvestList) {
            $invest = $investList->getInvestModel();
            $investSum = $investList->investment_sum;
            $investRs = $this->_calculatedInterestFormula($invest, $investSum);
        }
        return $investRs;
    }

    private function _calculatedInterestFormula(Invest $invest, $investSum)
    {
        $num = ($investSum * $invest->invest_date * ($invest->rate / 100)) / 12;
        return sprintf("%.2f", $num);
    }


}