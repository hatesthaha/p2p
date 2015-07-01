<?php
namespace frontend\controllers;

use common\models\Config;
use common\models\Debug;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use modules\asset\behaviors\ExperienceMoney;
use modules\asset\models\AssetMoney;
use modules\asset\models\BankCard;
use modules\invest\models\Invest;
use modules\invest\models\InvestList;
use modules\invest\models\Post;
use modules\member\models\LoginForm;
use modules\member\models\Member;
use modules\member\models\MemberOther;
use modules\member\models\VerificationCode;
use wanhunet\helpers\ErrorCode;
use wanhunet\helpers\JiuxinApi;
use wanhunet\helpers\Utils;
use wanhunet\phpqrcode\includes;
use wanhunet\phpqrcode\QRcode;
use wanhunet\wanhunet;
use Yii;
use yii\base\Event;
use yii\helpers\Url;

/**
 * Site controller
 */
class SiteController extends FrontendController
{

    public function actionIndex()
    {
//        return $this->renderPartial("index");
        return $this->redirect(Url::to(['site/enter']));
    }


    public function actionEnter()
    {
        Utils::ensureOpenId();

        if (wanhunet::$app->user->isGuest) {

            if (
                ($openId = wanhunet::$app->request->get('open_id')) !== null
                &&
                ($model = MemberOther::findOne(['row' => $openId, 'table' => MemberOther::TABLE_WECHAT])) !== null
            ) {
                /** @var MemberOther $model */
                if (Yii::$app->user->login($model->member, 3600 * 24 * 30)) {
                    return $this->redirect(Url::to(['main']));
                }
            }
            return $this->redirect(Url::to(['signin']));
        } else {
            return $this->redirect(Url::to(['signin']));
        }
    }

    public function actionSignup()
    {
        $param = [];
        if (wanhunet::$app->request->isAjax) {
            $vcode = new VerificationCode();
            $vcode->field = wanhunet::$app->request->post('phone');
            try {
                $save = $vcode->save();
                $param['status'] = $save ? 1 : 0;
                $param['status'] == 0 and $param['info'] = '验证码生成失败';
            } catch (\ErrorException $e) {
                $param['status'] = $e->getCode() == ErrorCode::Vcode_short_time ? 2 : 0;
                $param['info'] = $e->getMessage();
            }
        }
        return $this->view('signup', $param);
    }

    public function actionGetCaptcha()
    {

    }

    public function actionSignupVerify()
    {
        $request = wanhunet::$app->request;
        $phone = $request->post('phone');
        $captcha = $request->post('captcha');
        $pass = $request->post('password');
        $invitation = $request->post('invitation');
        $vcode = VerificationCode::findByField($phone);
        if ($vcode !== null && $vcode->verify($captcha)) {

            $model = new SignupForm();
            $model->username = $phone;
            $model->password = $pass;
            $model->parent = $invitation;

            if (($user = $model->signup()) !== null) {
                if (wanhunet::$app->getUser()->login($user)) {
                    $vcode->verifySave();
                    if ($request->post("action_do", null) === null) {
                        return $this->redirect(Url::to(['site/main']));
                    } else {
                        if ($request->post('wechat_id', null) !== null) {
                            $member = wanhunet::app()->member;
                            $wechatInfo = new MemberOther();
                            $wechatInfo->user_id = $member->id;
                            $wechatInfo->table = MemberOther::TABLE_WECHAT;
                            $wechatInfo->row = $request->post('wechat_id');
                            wanhunet::app()->member->setOtherInfo([$wechatInfo]);
                        }
                        return $this->redirect(Url::to(['share/signup-finish']));
                    }
                } else {
                    return $this->goBack([
                        'info' => '失败请重试',
                    ], Url::to(['site/signup']));
                }
            } else {
                return $this->goBack([
                    'info' => current($model->getFirstErrors()),
                    'signup.phone' => $phone,
                    'signup.show' => 2
                ], Url::to(['site/signup']));
            }
        } else {
            return $this->goBack([
                'info' => '验证码错误',
                'signup.phone' => $phone,
                'signup.show' => 2
            ], Url::to(['site/signup']));
        }
    }

    public function actionSignin()
    {
        $request = wanhunet::$app->request;

        if (!wanhunet::$app->user->getIsGuest()) {
            return $this->redirect(Url::to(['main']));
        }
        if ($request->isPost) {
            $login = new LoginForm($request->post());
            if ($login->login()) {
                return $this->redirect(Url::to(['main']));
            } else {
                return $this->goBack([
                    'info' => current($login->getFirstErrors()),
                    'phone' => $request->post('username'),
                ], Url::to(['signin']));
            }
        }

        return $this->view('signin');
    }

    public function actionLogout()
    {
        wanhunet::$app->user->logout();

        return $this->goBack([], Url::to(['signin']));
    }

    public function actionMain()
    {


        $investListBaseModel = InvestList::find()
            ->where(['status' => InvestList::STATUS_PAYED, 'member_id' => wanhunet::$app->user->getId()])
            ->orWhere(['status' => InvestList::STATUS_ORDER_TRIAL, 'member_id' => wanhunet::$app->user->getId()])
            ->joinWith([
                'invest' => function ($query) {
                    /** @var \yii\db\ActiveQuery $query */
                    $query->andWhere(['type' => Invest::TYPE_MONEY]);
                },
            ]);

        $investSum = clone $investListBaseModel;
        $investSum = Utils::moneyFormat($investSum->sum('investment_sum'));

        $allEarn = clone $investListBaseModel;
        $allEarn = $allEarn->andWhere(['<=', 'interest_time', time()])->sum('interest');

        $earnIng = clone $investListBaseModel;
        $earnIngAll = $earnIng->andWhere(['>=', 'interest_time', time()])->all();
        $earnIng = 0;
        /** @var InvestList[] $earnIngAll */
        foreach ($earnIngAll as $e) {
//            var_dump($e->getEarnings('now'));
            $earnIng += $e->getEarnings('now');
        }

        $yesterdayEarnIng = clone $investListBaseModel;
        $yesterdayEarnIngAll = $yesterdayEarnIng->andWhere(['>=', 'interest_time', time() - (3600 * 24)])->all();
        $yesterdayEarnIng = 0;
        /** @var InvestList[] $yesterdayEarnIngAll */
        foreach ($yesterdayEarnIngAll as $e) {
//            var_dump($e->getEarnings('1'));
            $yesterdayEarnIng += $e->getEarnings(1);
        }
        //////////////////////////////////
        $einvestListBaseModel = InvestList::find()
            ->where(['status' => InvestList::STATUS_PAYED, 'member_id' => wanhunet::$app->user->getId()])
            ->joinWith([
                'invest' => function ($query) {
                    /** @var \yii\db\ActiveQuery $query */
                    $query->andWhere(['type' => Invest::TYPE_EXPERIENCE_MONEY])->orWhere(['type' => Invest::TYPE_REG]);
                },
            ]);

        $einvestSum = clone $einvestListBaseModel;
        $einvestSum = Utils::moneyFormat($einvestSum->sum('investment_sum'));

        $eallEarn = clone $einvestListBaseModel;
        $eallEarn = $eallEarn->andWhere(['<=', 'interest_time', time()])->sum('interest');
        $eearnIng = clone $einvestListBaseModel;
        $eearnIngAll = $eearnIng->andWhere(['>=', 'interest_time', time()])->all();
        $eearnIng = 0;
        /** @var InvestList[] $eearnIngAll */
        foreach ($eearnIngAll as $e) {
            $eearnIng += $e->getEarnings('now');
        }
        $eyesterdayEarnIng = clone $einvestListBaseModel;
        $eyesterdayEarnIngAll = $eyesterdayEarnIng->andWhere(['>=', 'interest_time', time() - (3600 * 24)])->all();
        $eyesterdayEarnIng = 0;
        /** @var InvestList[] $eyesterdayEarnIngAll */
        foreach ($eyesterdayEarnIngAll as $e) {
            $eyesterdayEarnIng += $e->getEarnings(1);
        }

        return $this->view('main', [
            'sum' => $investSum,
            'earnSum' => $allEarn + $earnIng,
            'yesterdayEarnIng' => $yesterdayEarnIng,
            'esum' => $einvestSum,
            'eearnSum' => $eallEarn + $eearnIng,
            'eyesterdayEarnIng' => $eyesterdayEarnIng,
        ]);
    }

    public function actionSetup()
    {
        return $this->view('setup');
    }

    public function actionIdcard()
    {
        $request = wanhunet::$app->request;
        if ($request->isPost) {
            $member = wanhunet::app()->member;
            try {
                $member->idcard = $request->post('idcard');
                if (Member::find()->where(['idcard' => $request->post('idcard')])->count() > 0) {
                    throw new \ErrorException('该身份证号已存在');
                }
                $member->idcard_name = $request->post('idcard_name');
                $member->saveIdcard();
                return $this->goBack([
                    'info' => '认证成功',
                ], Url::to(['setup']));
            } catch (\ErrorException $e) {
                return $this->goBack([
                    'info' => $e->getMessage(),
                    'idcard' => $request->post('idcard'),
                    'idcard_name' => $request->post('idcard_name'),
                ], Url::to(['idcard']));
            }
        }
        return $this->view('idcard');
    }

    public function actionSetjiuxin()
    {
        $request = wanhunet::$app->request;

        if ($request->isPost) {
            $member = wanhunet::app()->member;
            try {
                $jiuxinId = JiuxinApi::jiuxinAuth($request->post('jiuxinusername'), $request->post('jiuxinpwd'));

                $info = $member->getOtherInfo(MemberOther::TABLE_JIUXIN);
                if ($info === null) {
                    $info = new MemberOther();
                }
                $info->table = MemberOther::TABLE_JIUXIN;
                $info->row = $request->post('jiuxinusername') . "=|=" . $jiuxinId;
                $info->user_id = wanhunet::$app->user->getId();
                $member->setOtherInfo([$info]);
                return $this->goBack([
                    'info' => '绑定成功',
                ], Url::to(['setup']));
            } catch (\ErrorException $e) {
                return $this->goBack([
                    'info' => $e->getMessage(),
                    'jiuxinusername' => $request->post('jiuxinusername'),
                    'jiuxinpwd' => $request->post('jiuxinpwd'),
                ], Url::to(['setjiuxin']));
            }
        }
        return $this->view('jiuxin');
    }

    public function actionEmail()
    {
        $request = wanhunet::$app->request;
        if ($request->isPost) {
            $email = $request->post('email');
            $vcode = VerificationCode::findByField($email);
            if ($vcode !== null) {
                if ($vcode->verify($request->post('captcha'))) {
                    $vcode->verifySave();
                    $member = wanhunet::app()->member;
                    $member->email = $email;
                    $member->email_status = Member::STATUS_HAVE_AUTH;
                    $member->save();
                    return $this->goBack([
                        'info' => '验证成功',
                    ], Url::to(['site/setup']));
                } else {
                    return $this->goBack([
                        'info' => '验证码错误',
                        'email' => $email
                    ], Url::to(['site/email']));
                }
            }
        }
        return $this->view('email');
    }

    public function actionEmailCaptcha()
    {
        $request = wanhunet::$app->request;
        $params = [];
        if ($request->isPost) {
            $email = $request->post('email');
            $params['email'] = $email;
            try {
                $vcode = new VerificationCode();
                $vcode->field = $email;
                $vcode->save();
                $params['info'] = '发送成功';
                $params['min'] = Config::getInstance()->getProperty('setTime.vcode');
            } catch (\ErrorException $e) {
                if ($e->getCode() == ErrorCode::Vcode_short_time) {
                    $params['min'] = $e->getMessage();
                    $params['info'] = '时间间隔过短';
                } else {
                    $params['info'] = $e->getMessage();
                }
            }
        }
        return $this->goBack($params, Url::to(['site/email']));
    }

    public function actionPrivilege()
    {
        $investSum = InvestList::find()
            ->where(['status' => InvestList::STATUS_PAYED, 'member_id' => wanhunet::$app->user->getId()])
            ->joinWith([
                'invest' => function ($query) {
                    /** @var \yii\db\ActiveQuery $query */
                    $query->andWhere(['type' => Invest::TYPE_MONEY]);
                },
            ])->sum('investment_sum');


        $friends = wanhunet::app()->member->findFriendIds();
        $fInveset = 0;
        if (count($friends) > 0) {
            $ids = [];
            foreach ($friends as $id) {
                $ids[] = $id['id'];
            }
            $fInveset = InvestList::find()
                ->where(['status' => InvestList::STATUS_PAYED, 'member_id' => $ids])
                ->joinWith([
                    'invest' => function ($query) {
                        /** @var \yii\db\ActiveQuery $query */
                        $query->andWhere(['type' => Invest::TYPE_MONEY]);
                    },
                ])->sum('investment_sum');

        }

        $emMaxList = AssetMoney::find()
            ->where(['type' => AssetMoney::TYPE_EXPERIENCE_MONEY_MAX, 'user_id' => wanhunet::$app->user->getId()])
            ->orderBy('id desc')
            ->joinWith([
                'actionUid'
            ])
            ->limit(50)
            ->all();
        $einvestListModle = InvestList::find()
            ->where(['status' => InvestList::STATUS_PAYED, 'member_id' => wanhunet::$app->user->getId()])
            ->joinWith(['invest' => function ($query) {
                /** @var \yii\db\ActiveQuery $query */
                $query->andWhere(['type' => Invest::TYPE_EXPERIENCE_MONEY]);
            }]);
        $einvestListSum = $einvestListModle->sum('interest');
        $einvestList = $einvestListModle->limit(50)->orderBy('id desc')->all();

        return $this->view('privilege', [
            'myInveset' => $investSum,
            'fInveset' => $fInveset,
            'emMaxList' => $emMaxList,
            'einvestList' => $einvestList,
            'einvestListSum' => $einvestListSum
        ]);
    }

    public function actionInformation()
    {
        if (($moneyModel = Invest::findByLately(Invest::TYPE_MONEY)) !== null) {
            $inverstOfMoney = Utils::timeCut($moneyModel->buy_time_start, time());
        } else {
            $inverstOfMoney = '未发布';
        }
        if (($emoneyModel = Invest::findByLately(Invest::TYPE_EXPERIENCE_MONEY)) !== null) {
            $inverstOfeMoney = Utils::timeCut($emoneyModel->buy_time_start, time());
        } else {
            $inverstOfeMoney = '未发布';
        }
        try {
            $jiuxinInfo = JiuxinApi::jiuxinGet();
            $row = wanhunet::app()->member->getOtherInfo(MemberOther::TABLE_JIUXIN)->row;
            $jiuxinInfo['j_user_name'] = current(explode('=|=', $row));
        } catch (\Exception $e) {
            $jiuxinInfo = [];
//
//            if ($e->getCode() == ErrorCode::Jiuxin_auth) {
//                $jiuxinInfo = [];
//            } else {
//            }
        }
        return $this->view('information', [
            'inverstOfMoney' => $inverstOfMoney,
            'inverstOfeMoney' => $inverstOfeMoney,
            'jiuxinInfo' => $jiuxinInfo,
            'actives' => Post::find()->where(['status' => Post::STATUS_ACTIVE])->orderBy('id desc')->all()
        ]);
    }

    public function actionRecharge()
    {
        $request = wanhunet::$app->request;
        $member = wanhunet::app()->member;
        $idcard = $member->idcard_status;
        if($idcard === Member::STATUS_NOT_HAVE_AUTH){
            return $this->goBack([
                'info' => '请先身份认证'
            ], Url::to(['site/idcard']));
        }
        if ($request->isPost) {
            //TODO
            $bank = wanhunet::app()->pay->bankCardQuery($request->post('backcardid'));
            if ($bank['ret_code'] === 5001) {
                return $this->goBack([
                    'info' => '修改成功'
                ], Url::to(['site/recharge']));
            } else {
                $unpayed = $member->setIncNewMoney(($request->post('step')));
                $urlManager = wanhunet::$app->urlManager;
                $unpayed->setUrl($urlManager->createAbsoluteUrl(['pay/pay-notify']), $urlManager->createAbsoluteUrl(['pay/pay-return']));
                $backcard = new BankCard();
                $backcard->cardId = $request->post('backcardid');
                $member->setBankCard($backcard);
                $member->saveBankCard();
                die($member->payOrderWithBankCard($unpayed, new BankCard([
                    'cardId' => $request->post('backcardid'),
                    'cardUserName' => $request->post('cardUserName')
                ])));
            }
        }
        $backcard = $member->getBankCard();
        if (count($backcard) > 0) {
            return $this->view('recharged');
        }
        return $this->view('recharge');
    }


    public function actionChangePassword()
    {
        $request = wanhunet::$app->request;
        if ($request->isPost) {
            $resetPass = new ResetPasswordForm($request->post());
            if ($resetPass->validate()) {
                if ($resetPass->save()) {
                    return $this->goBack([
                        'info' => '修改成功'
                    ], Url::to(['site/setup']));
                }
            } else {
                if ($resetPass->hasErrors()) {
                    return $this->goBack([
                        'info' => current($resetPass->getFirstErrors())
                    ], Url::to(['site/change-password']));
                }
            }
        }
        return $this->view('change_password');
    }

    public function actionChangePayPassword()
    {
        $request = wanhunet::$app->request;
        $member = wanhunet::app()->member;
        if ($request->isPost) {
            if ($request->post('newpass') !== $request->post('renewpass')) {
                return $this->goBack([
                    'info' => '两次输入的密码不同'
                ], Url::to(['site/change-pay-password']));
            }
            if ($member->validatePayPass($request->post('paypass'))) {
                if ($member->savePayPass($request->post('newpass'))) {
                    return $this->goBack([
                        'info' => '修改成功'
                    ], Url::to(['site/setup']));
                } else {
                    return $this->goBack([
                        'info' => '修改失败'
                    ], Url::to(['site/change-pay-password']));
                }
            } else {
                return $this->goBack([
                    'info' => '原取现密码错误'
                ], Url::to(['site/change-pay-password']));
            }
        }
        return $this->view('change_pay_password');
    }

    public function actionResetPayPass()
    {
        return $this->view('reset_pay_pass');
    }

    public function actionResetPayPassDo()
    {
        $member = wanhunet::app()->member;
        $request = wanhunet::$app->request;
        $param = [];
        if ($member->idcard == $request->post('idcard')) {
            $vcode = new VerificationCode();
            $vcode->field = $member->phone;
            try {
                $vcode->save();
            } catch (\ErrorException $e) {
                if ($e->getCode() == ErrorCode::Vcode_short_time) {
                    $param['time'] = $e->getMessage();
                } else {
                    return $this->goBack([
                        'info' => $e->getMessage()
                    ], Url::to(['site/reset-pay-pass']));
                }
            }

            return $this->view('reset_pay_pass_do', $param);
        } else {
            return $this->goBack([
                'info' => '身份证填写错误'
            ], Url::to(['site/reset-pay-pass']));
        }
    }

    public function actionResetPayPassDoPost()
    {
        $request = wanhunet::$app->request;
        $member = wanhunet::app()->member;
        if ($request->isPost) {
            if ($request->post('newpass') !== $request->post('renewpass')) {
                return $this->goBack([
                    'info' => '两次输入的密码不同'
                ], Url::to(['site/reset-pay-pass-do']));
            }
            $phone = $member->phone;
            $vcode = VerificationCode::findByField($phone);
            $vcode->field = $phone;
            if ($vcode->verify($request->post('captcha'))) {
                $vcode->verifySave();
                $member->savePayPass($request->post('newpass'));
                return $this->goBack([
                    'info' => '修改成功',
                ], Url::to(['site/setup']));
            } else {
                return $this->goBack([
                    'info' => '验证码错误',
                    'email' => $phone
                ], Url::to(['site/reset-pay-pass-do']));
            }
        }
        return false;
    }
    public function actionNihao(){
        JiuxinApi::jiuxinAuth('shiwolang12','123123');
    }

}
