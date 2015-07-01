<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>连连支付wap交易接口</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        ul, ol {
            list-style: none;
        }

        .title {
            color: #ADADAD;
            font-size: 14px;
            font-weight: bold;
            padding: 8px 16px 5px 10px;
        }

        .hidden {
            display: none;
        }

        .new-btn-login-sp {
            border: 1px solid #D74C00;
            padding: 1px;
            display: inline-block;
        }

        .new-btn-login {
            background-color: transparent;
            background-image: url("images/new-btn-fixed.png");
            border: medium none;
        }

        .new-btn-login {
            background-position: 0 -198px;
            width: 82px;
            color: #FFFFFF;
            font-weight: bold;
            height: 28px;
            line-height: 28px;
            padding: 0 10px 3px;
        }

        .new-btn-login:hover {
            background-position: 0 -167px;
            width: 82px;
            color: #FFFFFF;
            font-weight: bold;
            height: 28px;
            line-height: 28px;
            padding: 0 10px 3px;
        }

        .bank-list {
            overflow: hidden;
            margin-top: 5px;
        }

        .bank-list li {
            float: left;
            width: 153px;
            margin-bottom: 5px;
        }

        #main {
            width: 750px;
            margin: 0 auto;
            font-size: 14px;
            font-family: '宋体';
        }

        #logo {
            background-color: transparent;
            background-image: url("images/new-btn-fixed.png");
            border: medium none;
            background-position: 0 0;
            width: 166px;
            height: 35px;
            float: left;
        }

        .red-star {
            color: #f00;
            width: 10px;
            display: inline-block;
        }

        .null-star {
            color: #fff;
        }

        .content {
            margin-top: 5px;
        }

        .content dt {
            width: 160px;
            display: inline-block;
            text-align: right;
            float: left;

        }

        .content dd {
            margin-left: 100px;
            margin-bottom: 5px;
        }

        #foot {
            margin-top: 10px;
        }

        .foot-ul li {
            text-align: center;
        }

        .note-help {
            color: #999999;
            font-size: 12px;
            line-height: 130%;
            padding-left: 3px;
        }

        .cashier-nav {
            font-size: 14px;
            margin: 15px 0 10px;
            text-align: left;
            height: 30px;
            border-bottom: solid 2px #CFD2D7;
        }

        .cashier-nav ol li {
            float: left;
        }

        .cashier-nav li.current {
            color: #AB4400;
            font-weight: bold;
        }

        .cashier-nav li.last {
            clear: right;
        }

        .llpay_link {
            text-align: right;
        }

        .llpay_link a:link {
            text-decoration: none;
            color: #8D8D8D;
        }

        .llpay_link a:visited {
            text-decoration: none;
            color: #8D8D8D;
        }
    </style>
</head>
<body text=#000000 bgColor=#ffffff leftMargin=0 topMargin=4>
<div id="main">
    <div id="head">
        <span class="title">连连支付wap交易接口快速通道</span>
    </div>
    <div class="cashier-nav">
        <ol>
            <li class="current">1、确认信息 →</li>
            <li>2、点击确认 →</li>
            <li class="last">3、确认完成</li>
        </ol>
    </div>
    <form name=llpayment action='<?= \yii\helpers\Url::to(['pay/payapi']) ?>' method=post target="_blank">
        <div id="body" style="clear:left">
            <dl class="content">
                <dt>商户用户唯一编号：</dt>
                <dd>
                    <span class="null-star">*</span>
                    <input size="30" name="user_id" value="201408071000001543"/>
                        <span>必填
</span>
                </dd>
                <dt>商户业务类型：</dt>
                <dd>
                    <span class="null-star">*</span>
                    <input size="30" name="busi_partner" value="101001"/>
                        <span>必填  虚拟商品：101001 实物商品：109001 账户充值：108001
                        </span>
                </dd>
                <dt>商户唯一订单号：</dt>
                <dd>
                    <span class="null-star">*</span>
                    <input size="30" name="no_order" value="<?php echo uniqid() ?>"/>
                        <span>商户订单系统中唯一订单号，必填
</span>
                </dd>
                <dt>交易金额：</dt>
                <dd>
                    <span class="null-star">*</span>
                    <input size="30" name="money_order" value="0.01"/>
                        <span>必填
</span>
                </dd>
                <dt>商品名称：</dt>
                <dd>
                    <span class="null-star">*</span>
                    <input size="30" name="name_goods" value="羽毛球"/>
                        <span>
</span>
                </dd>
                <dt>订单描述
                    ：
                </dt>
                <dd>
                    <span class="null-star">*</span>
                    <input size="30" name="info_order" value="用户13958069593购买羽毛球3桶"/>
                    <span></span>
                </dd>

                <dt>卡号：</dt>
                <dd>
                    <span class="null-star">*</span>
                    <input size="30" name="card_no" value="6227000140510381491"/>
                        <span>
</span>
                </dd>
                <dt>姓名：</dt>
                <dd>
                    <span class="null-star">*</span>
                    <input size="30" name="acct_name" value="周中原"/>
                        <span>
</span>
                </dd>
                <dt>身份证号：</dt>
                <dd>
                    <span class="null-star">*</span>
                    <input size="30" name="id_no" value="130902199103153213"/>
                        <span>
</span>
                </dd>

                <dt>协议号：</dt>
                <dd>
                    <span class="null-star">*</span>
                    <input size="30" name="no_agree" value=""/>
                        <span>
</span>
                </dd>

                <dt>风险控制参数：</dt>
                <dd>
                    <span class="null-star">*</span>
                    <textarea rows="5" cols="60" size="30" name="risk_item">{\"user_info_bind_phone\":\"13958069593\",\"user_info_dt_register\":\"20131030122130\",\"risk_state\":\"1\",\"frms_ware_category\":\"1009\"}</textarea>
                        <span>
</span>
                </dd>
                <dt>订单有效时间：</dt>
                <dd>
                    <span class="null-star">*</span>
                    <input size="30" name="valid_order" value="10080"/>
                        <span>分钟为单位，默认为10080分钟（7天）
</span>
                </dd>

                <dt></dt>
                <dd>
                    <?= \wanhunet\helpers\Utils::csrfField() ?>
                    <span class="new-btn-login-sp">
                            <button class="new-btn-login" type="submit" style="text-align:center;">确 认</button>
                        </span>
                </dd>
            </dl>
        </div>
    </form>
    <div id="foot">
        <ul class="foot-ul">
            <li><font class="note-help">如果您点击“确认”按钮，即表示您同意该次的执行操作。 </font></li>
        </ul>
    </div>
</div>
</body>
</html>