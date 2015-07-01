<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\asset\models;


use common\models\Config;
use modules\member\models\Member;
use wanhunet\db\ActiveRecord;
use wanhunet\helpers\ErrorCode;
use wanhunet\helpers\Utils;
use yii\base\ErrorException;
use yii\db\IntegrityException;
use yii\web\HttpException;

/**
 * This is the model class for table "wh_asset".
 *
 * @property integer $id
 * @property string $wechat_id
 * @property integer $gift_limit
 * @property string $wechat_info
 * @property string $has_gift
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \modules\member\models\Member $user
 */
class ShareGift extends ActiveRecord
{
    const HAS_GIFT = 1;
    const NOT_HAS_GEFT = 0;

    public function behaviors()
    {
        return Utils::behaviorMerge(self::className(), parent::behaviors());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%share_gift}}';
    }

}