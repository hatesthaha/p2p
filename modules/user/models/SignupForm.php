<?php
namespace modules\user\models;

use common\models\User;
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
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'common\models\User', 'message' => '已被占用'],
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
            $user = new User();
            $user->username = $this->username;
            $user->setPassword($this->password);
            $user->email = $this->email;
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
