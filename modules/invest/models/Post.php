<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\invest\models;


use common\models\Config;
use wanhunet\base\Order;
use wanhunet\base\Product;
use wanhunet\db\ActiveRecord;
use wanhunet\helpers\ErrorCode;
use wanhunet\helpers\Utils;
use wanhunet\wanhunet;
use yii\base\ErrorException;

/**
 * This is the model class for table "{{%cms_post}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $alias
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Post extends ActiveRecord
{

    /**
     * 数据名称
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%invest_post}}';
    }

    /**
     * 数据库规则
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['content', 'alias'], 'string'],
            [[ 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * 数据库字段 Label
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '活动标题',
            'content' => '活动内容',
            'status' => '活动状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public static function findByAlias($alias)
    {
        return self::find()->where(['alias' => $alias])->orderBy('id desc')->one();
    }
}