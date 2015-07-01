<?php
namespace frontend\controllers;

use modules\asset\models\BankCard;
use modules\invest\models\Invest;
use modules\invest\models\InvestList;
use modules\member\models\LoginForm;
use modules\member\models\VerificationCode;
use wanhunet\base\Controller;
use wanhunet\helpers\ErrorCode;
use wanhunet\helpers\JiuxinApi;
use wanhunet\helpers\Utils;
use wanhunet\wanhunet;
use Yii;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SitedController extends Controller
{
//TODO
    public function actionNihao()
    {
        echo JiuxinApi::jiuxinAuth('shiwolang4', '123456');
    }

    public function actionAa()
    {
        $aa = JiuxinApi::jiuxinGet();
        print_r($aa);
    }
}
