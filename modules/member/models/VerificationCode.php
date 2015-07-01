<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\member\models;


use common\models\Config;
use modules\member\event\Verification;
use wanhunet\db\ActiveRecord;
use wanhunet\helpers\ErrorCode;
use wanhunet\helpers\Utils;
use yii\base\ErrorException;
use yii\validators\EmailValidator;

/**
 * Class VerificationCode
 * @package modules\member\models
 * @property integer $id
 * @property integer $field
 * @property string $code
 * @property integer $type
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class VerificationCode extends ActiveRecord
{
    const EVENT_VERIFY_SUCCESS_PHONE = 'verify_success_phone';
    const EVENT_VERIFY_SUCCESS_EMAIL = 'verify_success_email';

    const STATUS_VERIFY_SUCCESS = 10;
    const STATUS_VERIFY_UNSUCCESS = 0;

    const TYPE_PHONE = 10;
    const TYPE_EMAIL = 20;


    public function behaviors()
    {
        return Utils::behaviorMerge(self::className(), parent::behaviors());
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%verification_code}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => '验证码',
            'type' => '类型',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @param $name
     * @return array|null|\modules\member\models\VerificationCode
     */
    public static function findByField($name)
    {
        return self::find()->where(['field' => $name])->orderBy("id desc")->one();
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            if (empty($this->code)) {
                $this->code = rand(1000, 9999);
            }
            if (empty($this->type)) {
                if ((new EmailValidator())->validate($this->field)) {
                    $this->type = self::TYPE_EMAIL;
                } elseif (Utils::phoneVerify($this->field)) {
                    $this->type = self::TYPE_PHONE;
                } else {
                    throw new ErrorException('格式错误', ErrorCode::Vcode_not_field);
                }
                $this->_sendVcode();
            }
            if ($insert) {
                $this->status = self::STATUS_VERIFY_UNSUCCESS;
            }
        }
        return parent::beforeSave($insert);
    }


    //TODO 超时设置
    public function verify($code)
    {
        $rs = ($this->code == $code && $this->status == self::STATUS_VERIFY_UNSUCCESS);

        return $rs;
    }

    /**
     *
     */
    public function verifySave()
    {
        $isPhone = ($this->type == self::TYPE_PHONE);
        $this->verifySuccess($isPhone);
    }

    /**
     * @param $isPhone
     */
    protected function verifySuccess($isPhone)
    {

        $verificationEvent = new Verification();
        $verificationEvent->field = $this->field;
        $verificationEvent->created_at = $this->created_at;
        $verificationEvent->type = $this->type;
        $this->trigger(($isPhone ? self::EVENT_VERIFY_SUCCESS_PHONE : self::EVENT_VERIFY_SUCCESS_EMAIL), $verificationEvent);

        $this->status = self::STATUS_VERIFY_SUCCESS;
        $this->save();
    }

    /**
     * @throws ErrorException
     */
    private function _sendVcode()
    {
        $vcodeTime = Config::getInstance()->getProperty('setTime.vcode');
        $content = '您的验证码为：' . $this->code . '请在' . ($vcodeTime / 60) . '分钟内进行处理';
        if (($old = self::findByField($this->field)) !== null && $old->created_at > (time() - $vcodeTime)) {
            throw new ErrorException(($vcodeTime - (time() - $old->created_at)), ErrorCode::Vcode_short_time);
        }
        if ($this->type == self::TYPE_EMAIL) {
            try {
                Utils::sendEmail($this->field, '玖信贷邮箱验证码', $content);
            } catch (\Exception $e) {
                throw new ErrorException('验证码发送错误', ErrorCode::Send_email_error);
            }
        } else {
            if (Utils::sendSMS($this->field, $content) < 0) {
                throw new ErrorException('验证码发送错误', ErrorCode::Send_phone_error);
            }
        }
    }
}