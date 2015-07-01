<?php
namespace frontend\models;

use modules\member\models\Member;
use wanhunet\base\FormModel;
use Yii;

/**
 * Signup form
 */
class SignupForm extends FormModel
{
    public $username;
    public $password;
    public $parent;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'modules\member\models\Member', 'message' => '您的手机号已被占用'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => '密码'
        ];
    }


    /**
     * Signs user up.
     *
     * @return Member|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new Member();
            $user->username = $this->username;
            $user->phone = $this->username;
            $user->parent_id = $this->parent;
            $user->invitation = substr($this->username, -8, 8);
            $user->phone_status = Member::STATUS_HAVE_AUTH;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
