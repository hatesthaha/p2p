<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace wanhunet\components;

use wanhunet\authllpay\Included;
use wanhunet\authllpay\lib\JSON;
use wanhunet\base\PayComponent;
use wanhunet\helpers\Utils;
use yii\base\Component;
use yii\base\Event;

/**
 * Class llPayComponent
 * @package wanhunet\components
 * @var string $user_id 商户用户唯一编号
 * @var string $busi_partner 支付类型
 * @var string $no_order 商户网站订单系统中唯一订单号，必填
 * @var string $money_order 付款金额;
 * @var string $name_goods 商品名称;
 * @var string $info_order 订单描述
 * @var string $card_no 卡号
 * @var string $acct_name 姓名;
 * @var string $id_no 身份证号;
 * @var string $no_agree 协议号
 * @var string $risk_item 风险控制参数
 * @var string $valid_order 订单有效期
 * @var string $notify_url 服务器异步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数
 * @var string $return_url 页面跳转同步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class llPayComponent extends Component implements PayComponent
{
    const EVENT_SUCCESS_RETURN = 'successReturn';
    const EVENT_FAIL_RETURN = 'failReturn';
    const EVENT_SUCCESS_SYNRETURN = 'successSynReturn';
    const EVENT_FAIL_SYNRETURN = 'failSynReturn';
    const EVENT_SUCCESS_NOTIFY = 'successNotify';
    const EVENT_FAIL_NOTIFY = 'failNotify';


    public $llpay_config = [];

    public $notify_url;
    public $return_url;

    public $user_id,
        $busi_partner,
        $no_order,
        $name_goods,
        $info_order,
        $money_order,
        $card_no,
        $acct_name,
        $id_no,
        $no_agree,
        $risk_item;


    public function behaviors()
    {
        return Utils::behaviorMerge(self::className(), parent::behaviors());
    }

    public function setUrl($params)
    {
        $this->notify_url = $params['notify_url'];
        $this->return_url = $params['return_url'];
    }

    public function init()
    {
        parent::init();
        //↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓

        //商户编号是商户在连连钱包支付平台上开设的商户号码，为18位数字，如：201408071000001543
        $llpay_config['oid_partner'] = '201408071000001543';

        //安全检验码，以数字和字母组成的字符
        $llpay_config['key'] = '201408071000001543test_20140812';

        //↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

        //版本号
        $llpay_config['version'] = '1.2';

        //请求应用标识 为wap版本，不需修改
        $llpay_config['app_request'] = '3';


        //签名方式 不需修改
        $llpay_config['sign_type'] = strtoupper('MD5');

        //订单有效时间  分钟为单位，默认为10080分钟（7天）
        $llpay_config['valid_order'] = "10080";

        //字符编码格式 目前支持 gbk 或 utf-8
        $llpay_config['input_charset'] = strtolower('utf-8');

        //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        $llpay_config['transport'] = 'http';

        foreach ($llpay_config as $key => $value) {
            if (!isset($this->llpay_config[$key])) {
                $this->llpay_config[$key] = $value;
            }
        }
    }

    public function synReturn()
    {
        $llpayNotify = new \wanhunet\authllpay\lib\LLpayNotify($this->llpay_config);
        $verify_result = $llpayNotify->verifyReturn();
        if ($verify_result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取连连支付的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
            $json = new JSON();
            $res_data = $_GET["res_data"];


            //商户编号
            $rs['oid_partner'] = $oid_partner = $json->decode($res_data)->{'oid_partner'};

            //商户订单号
            $rs['no_order'] = $no_order = $json->decode($res_data)->{'no_order'};

            //支付结果
            $rs['result_pay'] = $result_pay = $json->decode($res_data)->{'result_pay'};
            $event = new PayEvent();
            $event->rs = $rs;
            $this->trigger(self::EVENT_SUCCESS_SYNRETURN, $event);

            if ($result_pay == 'SUCCESS') {
                $event = new PayEvent();
                $event->rs = $rs;
                $this->trigger(self::EVENT_SUCCESS_RETURN, $event);
                return true;
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（no_order）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
            } else {
                $event = new PayEvent();
                $event->rs = $rs;
                $this->trigger(self::EVENT_FAIL_RETURN, $event);
                return $result_pay;
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        } else {
            $this->trigger(self::EVENT_FAIL_SYNRETURN);
            //验证失败
            //如要调试，请看llpay_notify.php页面的verifyReturn函数
            return false;
        }
    }

    public function notifyReturn()
    {
        $llpayNotify = new \wanhunet\authllpay\lib\LLpayNotify($this->llpay_config);
        $llpayNotify->verifyNotify();
        if ($llpayNotify->result) { //验证成功
            //获取连连支付的通知返回参数，可参考技术文档中服务器异步通知参数列表
            $rs['no_order'] = $llpayNotify->notifyResp['no_order'];//商户订单号
            $rs['oid_paybill'] = $llpayNotify->notifyResp['oid_paybill'];//连连支付单号
            $rs['result_pay'] = $result_pay = $llpayNotify->notifyResp['result_pay'];//支付结果，SUCCESS：为支付成功
            $rs['money_order'] = $llpayNotify->notifyResp['money_order'];// 支付金额
            if ($result_pay == "SUCCESS") {
                //请在这里加上商户的业务逻辑程序代(更新订单状态、入账业务)
                //——请根据您的业务逻辑来编写程序——
                //payAfter($llpayNotify->notifyResp);

                $rs['notifyResp'] = $llpayNotify->notifyResp;
                $event = new PayEvent();
                $event->rs = $rs;
                $this->trigger(self::EVENT_SUCCESS_NOTIFY, $event);

            }
            file_put_contents("log.txt", "异步通知 验证成功\n", FILE_APPEND);
            die("{'ret_code':'0000','ret_msg':'交易成功'}"); //请不要修改或删除
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        } else {
            file_put_contents("log.txt", "异步通知 验证失败\n", FILE_APPEND);
            //验证失败

            $this->trigger(self::EVENT_FAIL_NOTIFY);

            die("{'ret_code':'9999','ret_msg':'验签失败'}");
            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }

    public function payApi()
    {
        new Included();
        $html = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>连连支付交易接口</title>
</head>
<body>
{{%content%}}
</body>
</html>
HTML;
        /**************************请求参数**************************/

        /*//商户用户唯一编号
        $user_id = $this->user_id;

        //支付类型
        $busi_partner = $_POST['busi_partner'];

        //商户订单号
        $no_order = $_POST['no_order'];
        //商户网站订单系统中唯一订单号，必填

        //付款金额
        $money_order = $_POST['money_order'];
        //必填

        //商品名称
        $name_goods = $_POST['name_goods'];

        //订单描述
        $info_order = $_POST['info_order'];

        //卡号
        $card_no = $_POST['card_no'];

        //姓名
        $acct_name = $_POST['acct_name'];

        //身份证号
        $id_no = $_POST['id_no'];

        //协议号
        $no_agree = $_POST['no_agree'];

        //风险控制参数
        $risk_item = $_POST['risk_item'];

        //订单有效期
        $valid_order = $_POST['valid_order'];

        //服务器异步通知页面路径
        $notify_url = $this->notify_url;
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $return_url = $this->return_url;
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/*/

        /************************************************************/

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "oid_partner" => trim($this->llpay_config['oid_partner']),
            "app_request" => trim($this->llpay_config['app_request']),
            "sign_type" => trim($this->llpay_config['sign_type']),
            "valid_order" => trim($this->llpay_config['valid_order']),
            "user_id" => $this->user_id,
            "busi_partner" => $this->busi_partner,
            "no_order" => $this->no_order,
            "dt_order" => local_date('YmdHis', time()),
            "name_goods" => $this->name_goods,
            "info_order" => $this->info_order,
            "money_order" => $this->money_order,
            "notify_url" => $this->notify_url,
            "url_return" => $this->return_url,
            "card_no" => $this->card_no,
            "acct_name" => $this->acct_name,
            "id_no" => $this->id_no,
            "no_agree" => $this->no_agree,
            "risk_item" => $this->risk_item,
        );

        //建立请求
        $llpaySubmit = new \wanhunet\authllpay\lib\LLpaySubmit($this->llpay_config);
        $html_text = $llpaySubmit->buildRequestForm($parameter, "post", "正在提交...");
        return str_replace('{{%content%}}', $html_text, $html);
    }

    public function bankCardQuery($card_no)
    {
        new Included();
        $parameter = array(
            "oid_partner" => trim($this->llpay_config['oid_partner']),
            "sign_type" => trim($this->llpay_config['sign_type']),
            'card_no' => $card_no,
        );
        $llpaySubmit = new \wanhunet\authllpay\lib\LLpaySubmit($this->llpay_config);
        $data = $llpaySubmit->buildRequestPara($parameter);
        $url = 'https://yintong.com.cn/traderapi/bankcardquery.htm';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        return json_decode($result, true);
    }


}