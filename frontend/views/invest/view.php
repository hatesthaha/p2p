<?php
/** @var $invest */
$web = \wanhunet\wanhunet::getAlias('@web') . '/';
$isGuest = \wanhunet\wanhunet::$app->user->isGuest;
$isEm = $invest['type'] == \modules\invest\models\Invest::TYPE_EXPERIENCE_MONEY;
$investList = \modules\invest\models\InvestList::find()
    ->where(['invest_id' => $invest['id'], \modules\invest\models\InvestList::tableName() . '.`status`' => \modules\invest\models\InvestList::STATUS_PAYED])
    ->joinWith(["member" => function ($q) {
        /** @var \yii\db\ActiveQuery $q */
        $q->select(\modules\member\models\Member::$SELECT_ROW);
    }])
    ->asArray()
    ->orderBy("id desc")
    ->limit(10)
    ->all();
$sum = ($invest['orderSum']['10'] + $invest['orderSum']['20']);
$sheng = $invest['amount'] - $sum;
if ($sheng <= 0) {
    $investListCount = \modules\invest\models\InvestList::find()
        ->where(['invest_id' => $invest['id'], \modules\invest\models\InvestList::tableName() . '.`status`' => \modules\invest\models\InvestList::STATUS_PAYED])
        ->count();
    $investListLast = \modules\invest\models\InvestList::find()
        ->where(['invest_id' => $invest['id'], \modules\invest\models\InvestList::tableName() . '.`status`' => \modules\invest\models\InvestList::STATUS_PAYED])
        ->asArray()
        ->orderBy("id desc")->one();

    $shouqingshijian = \wanhunet\helpers\Utils::timeCut($investListLast['pay_time'], $invest['buy_time_start']);

}
?>
<style type="text/css">
    @media screen and (min-width: 320px)
    body {
        font-size:

    16px

    ;
    }

    body {
        color: #3a3a3a;
        font-family: "Helvetica";
        font-size: 16px;
        background-color: #fff;
    }
</style>
<div class="main">
    <nav>
        <div class="nav">
            <a href="<?= \yii\helpers\Url::to(['site/main']) ?>" class="floatLeft logo"></a>
            <ul class="floatRight menu clearFloat">

                <?php if ($isGuest): ?>
                    <li><a href="<?= \yii\helpers\Url::to(['site/signin']) ?>" style="color:#0979a5;">登录</a></li>
                    <li><a href="<?= \yii\helpers\Url::to(['site/signup']) ?>" style="color:#0979a5;">注册</a></li>
                <?php else: ?>
                    <li style="background-color: #eaeaea;">
                        <a href="<?= \yii\helpers\Url::to(['invest/list']) ?>" style="color:#0979a5;">理财</a>
                    </li>
                    <li>
                        <a href="<?= \yii\helpers\Url::to(["site/setup"]) ?>" style="color:#0979a5;">
                            <img style="width:12px;height:12px;display:inline;" src="<?= $web ?>images/icon_peo.png"
                                 alt="">
                            我的
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </nav>
    <div class="bidCon">
        <div class="bidInfoBox prr">
            <?= $sheng <= 0 ? '<span class="zhang poo"></span>' : '' ?>
            <p>
                <span class="bidName">
                    <?php $invest_title = $invest['title']; ?>
                    <?php if ($isEm): ?>
                        <?php $innvest_title = '[体验标]' . $invest['title']; ?>
                    <?php endif; ?>
                    <?= mb_substr($invest_title, 0, 11, 'utf-8') ?>
                    <?= mb_strlen($invest_title, 'utf-8') > 11 ? "..." : '' ?>
                </span>
                <b>|</b>
                <span class="qiXiTime" data-timeout="2015-04-24 08:51:30">当日计息</span>
            </p>
            <ul class="infoList clearFloat">
                <li>
                    <span class="inName">预期年化收益</span><br>
                    <span class="shu c_red">
                        <?= sprintf("%.1f", $invest['rate']) ?>
                    </span><span class="ss">%</span>
                </li>
                <li>
                    <span class="inName">项目期限</span><br>
                    <span class="shu c_red"><?= $invest['invest_date'] ?></span>
                    <span class="ss">月</span>
                </li>
                <li>
                    <span class="inName">融资规模</span><br>
                    <span class="totalShu"><?= number_format($invest['amount']) ?></span><span class="ss">元</span>
                </li>
                <li>
                    <span class="inName">还款方式</span><br>
                    <span class="huanType"><?= $isEm ? "按月付息" : "每月还息，到期还本金" ?></span>
                </li>
            </ul>
            <?php $baifenbi = intval(($sum / $invest['amount']) * 100); ?>
            <div class="proBox clearFloat">
                <span class="proName">项目进度</span>

                <div class="proBg">
                    <div class="proGreen" style="width: <?= $baifenbi ?>%;"></div>
                </div>
                <b class="proNum"><?= $baifenbi ?></b><span class="proBai">%</span>
            </div>
        </div>
        <?php if ($isGuest): ?>
            <div class="accountBox">
                <ul>
                    <li>
                        <span class="lineName">请登录，</span>
                        <span class="lineName"><a href="#">立即登录&gt;&gt;</a></span>
                    </li>
                </ul>
            </div>
        <?php else: ?>
            <div class="accountBox">
                <ul>
                    <li>
                        <span
                            class="lineName">可用余额：<span><?= \wanhunet\wanhunet::app()->member->getMoney() ?></span>元
                            <a href="<?= \yii\helpers\Url::to(['site/recharge']) ?>">充值</a>
                        </span>
                        <?php if ($sheng <= 0): ?>
                            <div style="display: inline-block;float: right" class="licBtn LiGrayOut floatRight <?= $isEm ? 'em' : 'mm' ?>"
                                 id="<?= \wanhunet\wanhunet::$app->request->get('id') ?>">
                                已满标
                            </div>
                        <?php else: ?>
                            <div style="display: inline-block; float: right;font-size: 14px;"
                                 class="licBtn LiOrange floatRight <?= $isEm ? 'em' : 'mm' ?> payi"
                                 id="<?= \wanhunet\wanhunet::$app->request->get('id') ?>">
                                我要投
                            </div>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        <?php endif; ?>

        <style>
            .wanhu_item {
                margin-top: 5px;
                margin-bottom: 5px;;
            }
        </style>
        <div class="bidInfoBox prr wanhu_item">
            <p>项目详情</p>

            <div class="infoList clearFloat wanhu_content" style="display: none;">
                <?= $invest['introduce'] ?>
            </div>
        </div>

        <div class="bidInfoBox prr wanhu_item">
            <p>相关照片</p>

            <div class="infoList clearFloat wanhu_content" style="display: none;">
                <?php
                $imgs = json_decode($invest['imgs']);
                ?>
                <?php foreach ($imgs as $img): ?>
                    <img src="<?= \wanhunet\wanhunet::getAlias('@web') ?>/upload/<?= $img ?>" alt=""
                         style="max-width: 100%; height: auto;"/>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ($sheng <= 0): ?>
            <div class="accountBox">
                <ul>
                    <li style="font-size: 12px;">
                        已有
                        <span style="color: #eb5345;"><?= $investListCount ?></span>
                        笔购买，售罄历时
                        <?php echo $shouqingshijian; ?>
                       <!-- <span style="color: #3a3a3a;">00</span> 时
                        <span style="color: #3a3a3a;">00</span> 分
                        <span style="color: #3a3a3a;">00</span> 秒-->
                    </li>
                </ul>
            </div>
        <?php endif; ?>


        <table class="buyListBox">
            <thead>
            <tr>
                <td>投资用户</td>
                <td>投资资金</td>
                <td>投资时间</td>
            </tr>
            </thead>
            <tbody data-ptop="fulBid">
            <?php foreach ($investList as $i): ?>
                <tr>
                    <td>
                        <?= substr($i['member']['username'], 0, 3) ?>*****<?= substr($i['member']['username'], -3, 3) ?>
                    </td>
                    <td><?= $i['investment_sum'] ?>元</td>
                    <td><?= date("Y-m-d H:i:s", $i['pay_time']) ?></td>
                </tr>
            <?php endforeach; ?>


        </table>
    </div>
    <a class="btn_toTop" href="javascript:scroll(0,0)" style="display: none;">返回顶部</a>
</div>

<!--    弹出窗-->
<section class="zhezhao" id="zhezhao2">
</section>
<aside class="showDiv" id="showDiv2">
    <form action="<?= \yii\helpers\Url::to(['invest/pay']) ?>" method="post" id="form">
        <div class="zhe" id="zhe2" style="display: block; height:190px;">
            <p class="ablum-edit">
                <img id="zhe_close" width="15px;"
                     src="<?= \wanhunet\wanhunet::getAlias("@web") . '/' ?>images/smalldel.png" alt="">
            </p>

            <div class="clearFloat zhe-box">
                <p class="inv-je">
                    金额：
                    <input type='number' name="investment_sum" value=""/>
                    <input type='hidden' name="return_url" value="view"/>
                </p>

                <p style="font-size: 12px;">*转入当日开始计息，次日显示收益</p>

                <div id="label_radio">
                    <label for="radio_id_1" class="label_checked">账户余额支付</label>
                    <input type="radio" name="pay_style" value="balance" id="radio_id_1" checked="checked"/>
                    <br/>
                    <label for="radio_id_2" id="bank_label">银行卡支付</label>
                    <input type="radio" name="pay_style" value="bankcard" id="radio_id_2"/>
                </div>
            </div>
            <?= \wanhunet\helpers\Utils::csrfField() ?>
            <input type='hidden' value="" name="invest_id" id="invest_id"/>
            <span id="bto2">确认投资</span>
        </div>
    </form>
</aside>
<!--   END 弹出窗-->

<script>
    $(document).ready(function () {

        $(document).ready(function () {
            var UA = window.navigator.userAgent;  //使用设备
            var CLICK = "click";
            if (/ipad|iphone|android/.test(UA)) {   //判断使用设备
                CLICK = "tap";
            }
            $(".payi")[CLICK](function (event) {
                event.preventDefault();
                if ($(this).hasClass('em')) {
                    $("#bank_label").hide();
                } else {
                    $("#bank_label").show();
                }
                $("#zhezhao2").css("display", "block");
                $("#showDiv2").css("display", "block");
                $("#invest_id").val($(this).attr('id'));

            });
            $("#bto2")[CLICK](function () {
                $("#form").submit();
            });
            $("#zhe_close")[CLICK](function (event) {
                event.preventDefault();
                $("#zhezhao2").css("display", "none");
                $("#showDiv2").css("display", "none");
            });
            var labels = $("#label_radio label");
            var radios = $("#label_radio input");
            for (var i = 0; i < labels.length; i++) {
                labels[i].onclick = function () {
                    if (this.className == "") {
                        for (var j = 0; j < labels.length; j++) {
                            labels[j].className = "";
                            radios[j].checked = false;
                        }
                        this.className = 'label_checked';
                        try {
                            document.getElementById(this.name).checked = true;
                        } catch (e) {
                        }
                    }
                }
            }
        });

        $(".wanhu_item").click(function () {
            $(this).find(".wanhu_content").toggle();
        });

        $(function () {
            var now = 2;

            function tab() {
                $(".tabContent").removeClass('tabContentActive');
                $(".tabContent").eq(now).addClass('tabContentActive');
                var index = $(".tabContent").eq(now).index();
                $(".bannerImg a").hide().eq(index).show();
            }

            $(".tabContent").click(function () {
                now = $(this).index();
                tab();
            });
            function next() {
                now++;
                if (now == $(".tabContent").length) {
                    now = 0;
                }
                tab();
            }

            clearInterval(timer);
            var timer = setInterval(next, 5000);
            $(".bannerImg").mouseenter(function () {
                clearInterval(timer);
            });
            $(".itemBranch").mouseenter(function () {
                clearInterval(timer);
            });
            $(".bannerImg").mouseleave(function () {
                setInterval(next, 5000);
            });
            $(".itemBranch").mouseleave(function () {
                setInterval(next, 5000);
            });
            $(window).scroll(function () {
                if ($(this).scrollTop() >= 20) {
                    $(".btn_toTop").slideDown();
                } else {
                    $(".btn_toTop").slideUp();
                }
                ;
            });
        });
    });
</script>