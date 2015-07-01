<?php

namespace modules\cms\models;

use wanhunet\db\ActiveRecord;

/**
 * This is the model class for table "{{%cms_category}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $parent
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Category extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'parent'], 'required'],
            [['parent', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @return \modules\cms\models\Category
     * @throws \yii\web\NotFoundHttpException
     */
    public function getParent()
    {
        return self::staticRead($this->parent);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasMany(Post::className(), ['category_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'parent' => '父类ID',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }


    /**
     * @param int $pid
     * @param int $level
     * @param bool $onlyActive
     * @return array
     */
    public static function getCategoryTree($pid = 0, $level = 0, $onlyActive = true)
    {
        $where['parent'] = $pid;
        if ($onlyActive) {
            $where["status"] = Category::STATUS_ACTIVE;
        }
        $data = Category::find()->where($where)->asArray()->all();
        $tree = array();
        $level++;
        if (count($data) > 0) {
            foreach ($data as $v) {
                $child = self::getCategoryTree($v['id'], $level, $onlyActive);
                $tree[] = array('parent' => $v, 'child' => $child, 'level' => $level);
            }
        }
        return $tree;
    }

    /**
     * @param $data
     * @return array
     */
    public static function eachCategoryTree($data = null)
    {
        if ($data === null) $data = self::getCategoryTree(0);
        //目录树
        static $tree = array();
        if (!empty($data)) {
            foreach ($data as $v) {
                $parent = $v['parent'];
                $child = $v['child'];
                $tree[] = array(
                    'category_id' => $parent['id'],
                    'parent' => $parent['parent'],
                    'status' => $parent['status'],
                    'name' => '├' . str_repeat('─', ($v['level'] - 1)).'┤' . $parent['title']
                );
                self::eachCategoryTree($child);
            }
        }
        return array('data' => $tree, 'count' => count($tree));
    }
}
