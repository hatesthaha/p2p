<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\invest\models;


use wanhunet\db\ActiveRecord;

/**
 * Class InvestList
 * @package modules\invest\models
 * @property integer $id
 * @property integer $invest_list_id
 * @property integer $m_step
 * @property integer $m_status
 * @property integer $created_at
 * @property integer $updated_at
 * @property InvestList $investList
 *
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class InvestMonth extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%invest_month}}';
    }

    public function getInvestList()
    {
        return $this->hasOne(InvestList::className(), ['id' => 'invest_list_id']);
    }

    public static function get_record_status($status)
    {
        if ($status == self::STATUS_ACTIVE) {
            return '待返息';
        }
        if ($status == self::STATUS_DELETED) {
            return "已返息";
        }
        return $status;
    }
}