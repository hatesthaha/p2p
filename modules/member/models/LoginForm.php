<?php
namespace modules\member\models;

use wanhunet\base\FormModel;
use wanhunet\wanhunet;
use Yii;

/**
 * Login form
 */
class LoginForm extends FormModel
{
    public $username;
    public $password;
    public $open_id = null;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '您输入的用户名和密码不匹配！');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $rs = Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            if (!is_null($this->open_id)) {
                $otherInfo = MemberOther::findOne(['user_id' => wanhunet::$app->user->getId(), 'table' => MemberOther::TABLE_WECHAT]);
                $otherInfo = $otherInfo == null ? new MemberOther() : $otherInfo;
                $otherInfo->table = MemberOther::TABLE_WECHAT;
                $otherInfo->row = $this->open_id;
                wanhunet::app()->member->setOtherInfo([
                    $otherInfo
                ]);
            }
            return $rs;
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return Member|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Member::findByUsername($this->username);
        }

        return $this->_user;
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码'
        ];
    }

}
