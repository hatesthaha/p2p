<?php
namespace modules\member\models;

use modules\asset\models\Asset;
use wanhunet\db\ActiveRecord;
use wanhunet\helpers\ErrorCode;
use wanhunet\helpers\Utils;
use Yii;
use yii\base\ErrorException;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * Member model
 *
 * @property integer $id
 * @property string  $username
 * @property string  $auth_key
 * @property string  $password_hash
 * @property string  $password_reset_token
 * @property string  $idcard
 * @property string  $idcard_name
 * @property integer $idcard_status
 * @property string  $phone
 * @property integer $phone_status
 * @property string  $email
 * @property integer $email_status
 * @property integer $parent_id
 * @property Member  $parent
 * @property integer $status
 * @property integer $invitation
 * @property integer $created_at
 * @property integer $updated_at
 * @property array|MemberOther[] $otherInfos
 */
class Member extends ActiveRecord implements IdentityInterface
{
    /**
     * 认证状态，已认证
     */
    const STATUS_HAVE_AUTH = 10;
    /**
     * 认证状态，未认证
     */
    const STATUS_NOT_HAVE_AUTH = 0;

    /**
     * 日志事件
     */
    const EVENT_ACTION_LOG = 'actionLog';
    /**
     * 保存身份证，前置事件
     */
    const EVENT_BEFORE_SAVE_IDCARD = "beforeSaveIdcard";
    /**
     * 保存身份证，后置事件
     */
    const EVENT_AFTER_SAVE_IDCARD = "afterSsaveIdcard";

    public static $SELECT_ROW = [
        'id', 'username', 'phone', 'status', 'created_at', 'idcard',
        'idcard_name', 'idcard_status', 'email', 'email_status', 'invitation', 'parent_id'
    ];

    public static function get_auth_status($status)
    {
        switch ($status) {
            case self::STATUS_HAVE_AUTH:
                return '已认证';
                break;
            case self::STATUS_NOT_HAVE_AUTH:
                return '未认证';
                break;
            default:
                return $status;
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return Utils::behaviorMerge(self::className(), parent::behaviors());
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }


    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * 保存身份证
     * @return bool
     * @throws ErrorException
     */
    public function saveIdcard()
    {
        $verify = Utils::idcardVerify($this->idcard, $this->idcard_name);
        if ($verify == null) {
            $this->trigger(self::EVENT_BEFORE_SAVE_IDCARD);


            $this->idcard_status = self::STATUS_HAVE_AUTH;
            $save = $this->save();
            $this->trigger(self::EVENT_AFTER_SAVE_IDCARD);

            return $save;
        } else {
            throw new ErrorException($verify, ErrorCode::Idcard_validate_error);
        }
    }

    /**
     * 获取父类用户
     * @return null|IdentityInterface|static|Member|\modules\asset\behaviors\Asset
     */
    public function getParentModel()
    {
        return Member::find()->where(['invitation' => $this->parent_id])->one();
    }

    public function getParent()
    {
        return $this->hasOne(Member::className(), ['invitation' => $this->parent_id]);
    }

    /**
     * 手机号获取器
     * @return string
     */
    public function getPhone()
    {
        return substr($this->phone, 0, 3) . '****' . substr($this->phone, -4, 4);
    }

    /**
     * 身份证获取器
     * @return string
     */
    public function getIdcard()
    {
        return !empty($this->idcard) ? substr($this->idcard, 0, 6) . '*****' . substr($this->idcard, -4, 4) : '';
    }

    /**
     * 其他信息获取器
     * @return array|MemberOther[]
     */
    public function getOtherInfos()
    {
        return MemberOther::find()->where(['user_id' => $this->id])->all();
    }

    /**
     * @param $table
     * @return array|null|MemberOther
     */
    public function getOtherInfo($table)
    {
        return MemberOther::find()->where(['user_id' => $this->id, 'table' => $table])->one();
    }

    /**
     * 保存其他信息
     * @param array|MemberOther[] $otherInfos
     */
    public function setOtherInfo($otherInfos)
    {
        foreach ($otherInfos as $otherInfo) {
            $user_id = $otherInfo->user_id;
            if (empty($user_id)) {
                $otherInfo->user_id = $this->id;
            }
            $otherInfo->save();
        }
    }

    /**
     * 通过邀请码查找
     * @param $code
     * @return array|null|Member
     */
    public static function findByInvitation($code)
    {
        return Member::find()->where(['invitation' => $code])->one();
    }

    public function findFriends($count = false)
    {
        $member = Member::find()->where(['parent_id' => $this->invitation])->select(self::$SELECT_ROW);
        return $count ? $member->count() : $member->all();
    }

    public function findFriendIds()
    {
        return Member::find()->where(['parent_id' => $this->invitation])->asArray()->select(['id'])->all();
    }

    public $asset;

    public function getAsset()
    {
        return $this->hasOne(Asset::className(), ['user_id' => 'id']);
    }
}
