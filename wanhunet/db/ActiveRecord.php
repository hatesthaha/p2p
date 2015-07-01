<?php


namespace wanhunet\db;


use yii\behaviors\TimestampBehavior;
use yii\web\NotFoundHttpException;

/**
 * Class ActiveRecord
 * @package wanhunet\db
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * 软删除状态
     */
    const STATUS_DELETED = 0;
    /**
     * 活动状态
     */
    const STATUS_ACTIVE = 10;

    /**
     * 默认行为，插入更新时自动填充 create_at update_at 字段
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * 用于查找一条数据
     * @param int $id
     * @return static
     * @throws NotFoundHttpException
     */
    public static function staticRead($id = null)
    {
        if (($model = self::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('您访问的页面不存在！');
        }
    }

    /**
     * 动态判断修改是插入
     * @param $data
     * @param int $id
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    public static function staticSave($data, $id = null)
    {
        $modelName = self::className();
        $model = is_null($id) ? (new $modelName()) : (self::staticRead($id));
        $model->setAttributes($data);
        $model->save();
        return $model;
    }

    public static function get_record_status($status)
    {
        if ($status == self::STATUS_ACTIVE) {
            return '正常';
        }
        if ($status == self::STATUS_DELETED) {
            return "隐藏";
        }
        return $status;
    }

    /**
     * 用于删除一条数据
     * @param $id
     * @return ActiveRecord
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public static function staticDelete($id)
    {
        $model = self::staticRead($id);
        $model->delete();
        return $model;
    }

    /**
     * 用于软删除一条数据
     * @param $id
     * @return ActiveRecord
     */
    public static function staticSoftDel($id)
    {
        return self::staticSave(["status" => self::STATUS_DELETED], $id);
    }

    /**
     * 用于软查找一条数据
     * @param $id
     * @return static
     * @throws NotFoundHttpException
     */
    public static function staticSoftRead($id)
    {
        if (($model = self::findOne(["id" => $id, "status" => self::STATUS_ACTIVE])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('您访问的页面不存在！');
        }
    }


    /**
     * 用于转换为JSON数据格式
     * @param array $fields
     * @param array $expand
     * @param bool $recursive
     * @return string
     */
    public function toJson(array $fields = [], array $expand = [], $recursive = true)
    {
        return json_encode($this->toArray($fields, $expand, $recursive));
    }


}