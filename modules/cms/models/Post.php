<?php

namespace modules\cms\models;

use wanhunet\db\ActiveRecord;

/**
 * This is the model class for table "{{%cms_post}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $alias
 * @property integer $category_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property \modules\cms\models\Category $category
 */
class Post extends ActiveRecord
{

    /**
     * 数据名称
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_post}}';
    }

    /**
     * 数据库规则
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'category_id'], 'required'],
            [['content', 'alias'], 'string'],
            [['category_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * 定义关联关系
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getCategoryModel()
    {
        return Category::findOne($this->category_id);
    }

    /**
     * 数据库字段 Label
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '文章标题',
            'content' => '文章内容',
            'category_id' => '文章分类',
            'status' => '文章状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public static function findByAlias($alias)
    {
        return self::find()->where(['alias' => $alias])->orderBy('id desc')->one();
    }
}
