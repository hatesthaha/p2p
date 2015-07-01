<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace wanhunet\helpers;


use modules\member\models\MemberOther;
use wanhunet\wanhunet;
use yii\base\ErrorException;

/**
 * Class JiuxinApi
 * @package wanhunet\helpers
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class JiuxinApi
{
    public static $errors = [
        '1001' => '验证签名失败',
        '1002' => '微信用户名为空',
        '1003' => '微信用户ID为空空',
        '1004' => '玖信贷用户名为空',
        '1005' => '玖信贷密码为空',
        '1006' => '玖信贷密码为空玖信贷密码为空',
        '1007' => '绑定玖信贷用户不存在',
        '1008' => '玖信贷密码错误',
        '1009' => '微信用户已在玖信贷绑定',
        '1010' => '玖信贷账户已绑定微信端账户',
        '1011' => '绑定失败',
        '2001' => '验证签名失败',
        '2002' => '账户非绑定关系'
    ];

    /**
     * @param $username
     * @param $pwd
     * @return bool
     * @throws ErrorException
     */
    public static function jiuxinAuth($username, $pwd)
    {

        $w_username = wanhunet::app()->member->username;
        $w_user_id = wanhunet::$app->user->getId();
        $j_username = $username;
        $j_password = md5($pwd);
        $key = 'asldkhn92y3rnsdfh903rfbpfsgf3h9uh3JHGJH';
        $sign = sha1(md5($w_username . '&' . $w_user_id . '&' . $j_username . '&' . $j_password . '&' . $key));

        $data = [
            'w_username' => $w_username,
            'w_user_id' => $w_user_id,
            'j_username' => $j_username,
            'j_password' => $j_password,
            'key' => $key,
            'sign' => $sign,
        ];
        $uri = "http://test.jiuxindai.com/communication/jborrow/winit";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'root:jxd123456');
        $return = curl_exec($ch);
        $return = str_replace('app', "", $return);
        curl_close($ch);
        $requests = json_decode($return, true);
        if (isset($requests['j_user_id'])) {
            return $requests['j_user_id'];
        } else {
            throw new ErrorException(self::$errors[$return], ErrorCode::Jiuxin_auth);
        }
    }

    public static function jiuxinGet()
    {
        $member = wanhunet::app()->member;
        $key = '988Kgho90LKH8ION9n9oH098hhnoph908Hh9';
        $row = $member->getOtherInfo(MemberOther::TABLE_JIUXIN)->row;
        $j_user_id = (explode('=|=', $row));
        $j_user_id = $j_user_id[1];
        $w_user_id = $member->getId();
        $data = [
            'j_user_id' => $j_user_id,
            'w_user_id' => $w_user_id,
            'sign' => sha1(md5($j_user_id . '&' . $w_user_id . '&' . $key))
        ];
        $uri = "http://test.jiuxindai.com/communication/jborrow/getuseraccount";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'root:jxd123456');
        $return = curl_exec($ch);
        $return = str_replace('app', "", $return);
        curl_close($ch);
        $requests = json_decode($return, true);
        if (is_array($requests)) {
            return $requests;
        } else {
            throw new ErrorException(self::$errors[$return], ErrorCode::Jiuxin_auth);
        }
    }
}