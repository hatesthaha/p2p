<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace common\models;


use yii\db\Query;

class Config
{
    private static $configd =
        [
            'setIncEM.verify_success_email' => 10,        //验证邮箱成功
            'setIncEM.verify_success_phone' => 20,        //验证手机成功
            'setIncEM.firstMoney' => 30,                  //第一次充值
            'setIncEM.firstIdcard' => 100,                //填写身份证
            'setIncEM.jiuxin' => 100,                     //绑定玖信账号
            'setIncEM.wechat' => 100,                     //绑定玖信账号
            'setIncEM.invitationParent' => 13,            //用户通过邀请码注册
            'setIncEM.invitationParentConut9' => 14,      //用户通过邀请码注册
            'setIncEM.invitationConut9' => 15,            //用户通过邀请码注册
            'setIncEM.gift' => "1000,2000,5000,9000,7500",//红包枚举
            'setIncEM.initEmMax' => 500,            //用户通过邀请码注册

            'setTime.orderExpire' => 3600,         //订单超时时间 （秒）
            'setTime.vcode' => 60,                //验证码超时时间（秒）

            'setIncMax.Inc' => 1000,

            'Invest.sumConfig' => 'both',           //TODO 这个建议使用both

            'assetLog' => [
                'site/setjiuxin' => "绑定玖信贷",
                'site/recharge' => '充值',
                'pay/pay-with-balance' => '投资',
                'invest/pay' => '投资',
                'invest/mention-post' => '提现',
                'pay/mention' => '提现',
                'wechat/login' => '绑定微信',
                'site/idcard' => '认证身份证',
                'site/email' => '认证邮箱',
                'site/signup-verify' => '认证手机',
                'invest/list/return-rate' => '返息',
                'invest/list/return-rate-month' => '返息'
            ]
        ];
    public $config = [];
    private static $_instance;

    public function __get($name)
    {
        return $this->config[$name];
    }

    private function __construct()
    {
        $query = (new Query())->from('wh_config')->all();
        $this->config = self::$configd;
        foreach ($query as $q) {
            $a[$q['key']] = $q['value'];
            $this->config = array_merge(self::$configd, $a);
        }
    }


    public function __clone()
    {
        return self::$_instance;
    }

    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function getProperty($name)
    {
        return $this->hasProperty($name) ? $this->config[$name] : null;
    }

    public function hasProperty($name)
    {
        return isset($this->config[$name]);
    }

}