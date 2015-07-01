<?php
namespace frontend\models;

use common\models\User;
use wanhunet\base\FormModel;
use wanhunet\wanhunet;
use yii\base\ErrorException;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * @property \modules\member\models\Member|\modules\asset\behaviors\Asset $_user
 * Password reset form
 */
class ResetPasswordForm extends FormModel
{
    public $password;
    public $newpassword;
    public $renewpassword;

    private $_user;

    public function init()
    {
        parent::init();
        $this->_user = wanhunet::app()->member;
        if ($this->_user === null) {
            throw new ErrorException("请登陆");
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'newpassword', 'renewpassword'], 'required'],
            [['password', 'newpassword', 'renewpassword'], 'string', 'min' => 6],
            [['renewpassword'], 'compare', 'compareAttribute' => 'newpassword'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->_user;
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '您输入的用户名和密码不匹配！');
            }
        }
    }

    public function save()
    {
        $user = $this->_user;
        $user->setPassword($this->newpassword);
        $user->generatePasswordResetToken();
        return $user->save();
    }

    public function attributeLabels()
    {
        return [
            'password' => '密码',
            'newpassword' => '新密码',
            'renewpassword' => '重复新密码'
        ];
    }


}
