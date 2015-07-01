<?php
namespace wanhunet\authllpay\lib;
/* *
 * 类名：LLpaySubmit
 * 功能：连连支付接口请求提交类
 * 详细：构造连连支付各接口表单HTML文本，获取远程HTTP数据
 * 版本：1.1
 * 日期：2014-04-16
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */
require_once("llpay_core.function.php");
require_once("llpay_md5.function.php");

class LLpaySubmit
{

    var $llpay_config;
    /**
     *连连认证支付网关地址
     *
     */
    var $llpay_gateway_new = 'https://yintong.com.cn/llpayh5/authpay.htm';

    function __construct($llpay_config)
    {
        $this->llpay_config = $llpay_config;
    }

    function LLpaySubmit($llpay_config)
    {
        $this->__construct($llpay_config);
    }

    /**
     * 生成签名结果
     * @param $para_sort 已排序要签名的数组
     * return 签名结果字符串
     */
    function buildRequestMysign($para_sort)
    {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = createLinkstring($para_sort);
        $mysign = "";
        //PHP5.3 版本以上 风控参数去斜杠
        $prestr = stripslashes($prestr);
        file_put_contents("log.txt", "新的签名:" . $prestr . "\n", FILE_APPEND);
        switch (strtoupper(trim($this->llpay_config['sign_type']))) {
            case "MD5" :
                $mysign = md5Sign($prestr, $this->llpay_config['key']);
                break;
            default :
                $mysign = "";
        }
        file_put_contents("log.txt", "签名:" . $mysign . "\n", FILE_APPEND);
        return $mysign;
    }

    /**
     * 生成要请求给连连支付的参数数组
     * @param string $para_temp 请求前的参数数组
     * @return string 要请求的参数数组
     */
    function buildRequestPara($para_temp)
    {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = paraFilter($para_temp);
        //对待签名参数数组排序
        $para_sort = argSort($para_filter);
        //生成签名结果
        $mysign = $this->buildRequestMysign($para_sort);
        //签名结果与签名方式加入请求提交参数组中
        $para_sort['sign'] = $mysign;
        $para_sort['sign_type'] = strtoupper(trim($this->llpay_config['sign_type']));
        foreach ($para_sort as $key => $value) {
            $para_sort[$key] = urlencode($value);
        }
        return urldecode(json_encode($para_sort));
    }

    /**
     * 生成要请求给连连支付的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组字符串
     */
    function buildRequestParaToString($para_temp)
    {
        //待请求参数数组
        $para = $this->buildRequestPara($para_temp);

        //把参数组中所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
        $request_data = createLinkstringUrlencode($para);

        return $request_data;
    }

    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param array $para_temp 请求参数数组
     * @param string $method 提交方式。两个值可选：post、get
     * @param string $button_name 确认按钮显示文字
     * @return string 提交表单HTML文本
     */
    function buildRequestForm($para_temp, $method, $button_name)
    {
        //待请求参数数组
        $para = $this->buildRequestPara($para_temp);
        $sHtml = "<form id='llpaysubmit' name='llpaysubmit' action='" . $this->llpay_gateway_new . "' method='" . $method . "'>";
        $sHtml .= "<input type='hidden' name='req_data' value='" . $para . "'/>";
        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml . "<input type='submit' value='" . $button_name . "'></form>";
        $sHtml = $sHtml . "<script>document.forms['llpaysubmit'].submit();</script>";
        return $sHtml;
    }

    /**
     * 建立请求，以模拟远程HTTP的POST请求方式构造并获取连连支付的处理结果
     * @param $para_temp 请求参数数组
     * @return 连连支付处理结果
     */
    function buildRequestHttp($para_temp)
    {
        $sResult = '';

        //待请求参数数组字符串
        $request_data = $this->buildRequestPara($para_temp);

        //远程获取数据
        $sResult = getHttpResponsePOST($this->llpay_gateway_new, $this->llpay_config['cacert'], $request_data, trim(strtolower($this->llpay_config['input_charset'])));

        return $sResult;
    }

    /**
     * 建立请求，以模拟远程HTTP的POST请求方式构造并获取连连支付的处理结果，带文件上传功能
     * @param $para_temp 请求参数数组
     * @param $file_para_name 文件类型的参数名
     * @param $file_name 文件完整绝对路径
     * @return 连连支付返回处理结果
     */
    function buildRequestHttpInFile($para_temp, $file_para_name, $file_name)
    {

        //待请求参数数组
        $para = $this->buildRequestPara($para_temp);
        $para[$file_para_name] = "@" . $file_name;

        //远程获取数据
        $sResult = getHttpResponsePOST($this->llpay_gateway_new, $this->llpay_config['cacert'], $para, trim(strtolower($this->llpay_config['input_charset'])));

        return $sResult;
    }

    /**
     * 用于防钓鱼，调用接口query_timestamp来获取时间戳的处理函数
     * 注意：该功能PHP5环境及以上支持，因此必须服务器、本地电脑中装有支持DOMDocument、SSL的PHP配置环境。建议本地调试时使用PHP开发软件
     * return 时间戳字符串
     */
    function query_timestamp()
    {
        $url = $this->llpay_gateway_new . "service=query_timestamp&partner=" . trim(strtolower($this->llpay_config['partner'])) . "&_input_charset=" . trim(strtolower($this->llpay_config['input_charset']));
        $encrypt_key = "";

        $doc = new \DOMDocument();
        $doc->load($url);
        $itemEncrypt_key = $doc->getElementsByTagName("encrypt_key");
        $encrypt_key = $itemEncrypt_key->item(0)->nodeValue;

        return $encrypt_key;
    }
}

?>