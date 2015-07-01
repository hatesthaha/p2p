<?php
/* * 
 * 功能：连连支付页面跳转同步通知页面
 * 版本：1.2
 * 日期：2014-06-13
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见llpay_notify_class.php中的函数verifyReturn
 */

require_once("llpay.config.php");
require_once("lib/llpay_notify.class.php");
include_once ('lib/llpay_cls_json.php');
?>
<!DOCTYPE HTML>
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
//计算得出通知验证结果
$llpayNotify = new \wanhunet\authllpay\lib\LLpayNotify($llpay_config);
$verify_result = $llpayNotify->verifyReturn();
if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代码
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取连连支付的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
	$json = new JSON;
	$res_data = $_GET["res_data"];


	//商户编号
	$oid_partner = $json->decode($res_data)-> {'oid_partner' };

	//商户订单号
	$no_order = $json->decode($res_data)-> {'no_order' };

	//支付结果
	$result_pay =  $json->decode($res_data)-> {'result_pay' };

    if($result_pay == 'SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（no_order）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
    }
    else {
      echo "result_pay=".$result_pay;
    }
		
	echo "验证成功<br />";

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    //如要调试，请看llpay_notify.php页面的verifyReturn函数
    echo "验证失败";
}
?>
        <title>连连支付wap交易接口</title>
	</head>
    <body>
    </body>
</html>