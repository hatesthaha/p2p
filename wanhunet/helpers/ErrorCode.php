<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace wanhunet\helpers;


/**
 * Class ErrorCode
 * @package wanhunet\helpers
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class ErrorCode
{
    const Idcard_validate_error = '510';          //身份证验证错误
    const Pay_pass_empty = '520';                 //支付密码为空
    const Buy_each_max = '530';                   //购买超出最大限制
    const Buy_each_min = '540';                   //购买超出最小限制
    const Buy_out_time = '550';                   //不在购买时间内
    const Buy_experience_money_max = '560';       //购买超出个人投资多体验金
    const Buy_has_buy = '570';                    //已经购买过了
    const Asset_money_minus = '580';              //账号金额负数
    const Asset_experience_money_minus = '590';   //体验金负数
    const Pay_had_pay = '600';                    //账单已支付
    const Send_phone_error = '610';               //短信信息发送错误
    const Send_email_error = '620';               //邮箱信息发送错误
    const Vcode_short_time = '630';               //验证码发送过于频繁
    const Jiuxin_auth = '640';                    //玖信验证用户密码
    const Vcode_not_field = '650';                //验证格式不能通过

}