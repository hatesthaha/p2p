<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\member\models;


use wanhunet\db\ActiveRecord;
use wanhunet\helpers\ErrorCode;
use wanhunet\helpers\Utils;
use yii\base\ErrorException;

/**
 * Class MemberOther
 * @package modules\member\models
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $table
 * @property string $row
 * @property integer $created_at
 * @property integer $updated_at
 * @property Member $member
 *
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class MemberOther extends ActiveRecord
{
    const TABLE_WECHAT = 'wechat';
    const TABLE_JIUXIN = 'jiuxin';

    private $_username, $_pwd;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_other}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['table', 'row'], 'string', 'max' => 255]
        ];
    }

    public function behaviors()
    {
        return Utils::behaviorMerge(self::className(), parent::behaviors());
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'table' => 'Table',
            'row' => 'Row',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->_username = $username;
    }

    /**
     * @param mixed $pwd
     */
    public function setPwd($pwd)
    {
        $this->_pwd = $pwd;
    }

    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'user_id']);
    }


}