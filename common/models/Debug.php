<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace common\models;


use wanhunet\db\ActiveRecord;

/**
 * Class Debug
 * @package common\models
 *
 * @property integer $id
 * @property string $json
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class Debug extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%debug}}';
    }

    public static function get()
    {
        $data = self::find()->orderBy('id desc')->asArray()->all();
        foreach ($data as $v) {
            $v['json'] = json_decode($v['json'], true);
            print_r($v);
        }

    }

    public static function add($value)
    {
        $value = json_encode($value);

        $self = new Debug();
        $self->json = $value;
        $self->save();
        return $self;
    }
}