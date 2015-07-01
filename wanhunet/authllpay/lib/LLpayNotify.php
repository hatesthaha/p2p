<?php

/* *
 * 类名：LLpayNotify
 * 功能：连连支付通知处理类
 * 详细：处理连连支付各接口通知返回
 * 版本：1.1
 * 日期：2014-04-16
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

 *************************注意*************************
 * 调试通知返回时，可查看或改写log日志的写入TXT里的数据，来检查通知返回是否正常
 */
namespace wanhunet\authllpay\lib;

require_once("llpay_core.function.php");
require_once("llpay_md5.function.php");


class LLpayNotify
{
    var $llpay_config;
    var $notifyResp = array();
    var $result = false;

    function __construct($llpay_config)
    {
        $this->llpay_config = $llpay_config;
    }

    function LLpayNotify($llpay_config)
    {
        $this->__construct($llpay_config);
    }


    /**
     * 针对notify_url验证消息是否是连连支付发出的合法消息
     * @return 验证结果
     */
    function verifyNotify()
    {
        //生成签名结果
        $is_notify = true;
        $json = new JSON;
        $str = file_get_contents("php://input");
        $val = $json->decode($str);
        $oid_partner = getJsonVal($val, 'oid_partner');
        $sign_type = getJsonVal($val, 'sign_type');
        $sign = getJsonVal($val, 'sign');
        $dt_order = getJsonVal($val, 'dt_order');
        $no_order = getJsonVal($val, 'no_order');
        $oid_paybill = getJsonVal($val, 'oid_paybill');
        $money_order = getJsonVal($val, 'money_order');
        $result_pay = getJsonVal($val, 'result_pay');
        $settle_date = getJsonVal($val, 'settle_date');
        $info_order = getJsonVal($val, 'info_order');
        $pay_type = getJsonVal($val, 'pay_type');
        $bank_code = getJsonVal($val, 'bank_code');
        $no_agree = getJsonVal($val, 'no_agree');
        $id_type = getJsonVal($val, 'id_type');
        $id_no = getJsonVal($val, 'id_no');
        $acct_name = getJsonVal($val, 'acct_name');

        //首先对获得的商户号进行比对
        if ($oid_partner != $this->llpay_config['oid_partner']) {
            //商户号错误
            return;
        }
        $parameter = array(
            'oid_partner' => $oid_partner,
            'sign_type' => $sign_type,
            'dt_order' => $dt_order,
            'no_order' => $no_order,
            'oid_paybill' => $oid_paybill,
            'money_order' => $money_order,
            'result_pay' => $result_pay,
            'settle_date' => $settle_date,
            'info_order' => $info_order,
            'pay_type' => $pay_type,
            'bank_code' => $bank_code,
            'no_agree' => $no_agree,
            'id_type' => $id_type,
            'id_no' => $id_no,
            'acct_name' => $acct_name
        );
        if (!$this->getSignVeryfy($parameter, $sign)) {
            return;
        }
        $this->notifyResp = $parameter;
        $this->result = true;
        return true;
    }

    /**
     * 针对return_url验证消息是否是连连支付发出的合法消息
     * @return  验证结果
     */
    function verifyReturn()
    {
        if (empty ($_POST)) { //判断POST来的数组是否为空
            return false;
        } else {

            $res_data = $_POST["res_data"];
            //  file_put_contents("log.txt", "返回结果:" . $res_data . "\n", FILE_APPEND);
            $json = new JSON;
            //error_reporting(3);
            //商户编号
            $oid_partner = $json->decode($res_data)->{'oid_partner'};

            //首先对获得的商户号进行比对
            if (trim($oid_partner) != $this->llpay_config['oid_partner']) {
                //商户号错误
                return false;
            }

            //生成签名结果
            $parameter = array(
                'oid_partner' => $oid_partner,
                'sign_type' => $json->decode($res_data)->{'sign_type'},
                'dt_order' => $json->decode($res_data)->{'dt_order'},
                'no_order' => $json->decode($res_data)->{'no_order'},
                'oid_paybill' => $json->decode($res_data)->{'oid_paybill'},
                'money_order' => $json->decode($res_data)->{'money_order'},
                'result_pay' => $json->decode($res_data)->{'result_pay'},
                'settle_date' => $json->decode($res_data)->{'settle_date'},
                'info_order' => $json->decode($res_data)->{'info_order'},
                'pay_type' => $json->decode($res_data)->{'pay_type'},
                'bank_code' => $json->decode($res_data)->{'bank_code'},
            );

            if (!$this->getSignVeryfy($parameter, $json->decode($res_data)->{'sign'})) {
                return false;
            }
            return true;

        }
    }

    /**
     * 获取返回时的签名验证结果
     * @param $para_temp 通知返回来的参数数组
     * @param $sign 返回的签名结果
     * @return 签名验证结果
     */
    function getSignVeryfy($para_temp, $sign)
    {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = paraFilter($para_temp);

        //对待签名参数数组排序
        $para_sort = argSort($para_filter);

        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = createLinkstring($para_sort);

        // file_put_contents("log.txt", "原串:" . $prestr . "\n", FILE_APPEND);
        // file_put_contents("log.txt", "sign:" . $sign . "\n", FILE_APPEND);
        $isSgin = false;
        switch (strtoupper(trim($this->llpay_config['sign_type']))) {
            case "MD5" :
                $isSgin = md5Verify($prestr, $sign, $this->llpay_config['key']);
                break;
            default :
                $isSgin = false;
        }

        return $isSgin;
    }

}