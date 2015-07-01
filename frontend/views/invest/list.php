<?php
$web = \wanhunet\wanhunet::getAlias('@web') . '/';
/** @var modules\invest\models\Invest[] $experiences */
/** @var modules\invest\models\Invest[] $moneys */
$isGuest = \wanhunet\wanhunet::$app->user->isGuest;
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
            <a href="#" class="floatLeft logo"></a>
            <ul class="floatRight menu clearFloat">
                <?php if ($isGuest): ?>
                    <li><a href="<?= \yii\helpers\Url::to(['site/signin']) ?>" style="color:#0979a5;">登录</a></li>
                    <li><a href="<?= \yii\helpers\Url::to(['site/signup']) ?>" style="color:#0979a5;">注册</a></li>
                <?php else: ?>
                    <li style="background-color: #eaeaea;">
                        <a href="javascript:" style="color:#0979a5;">理财</a>
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
    <div class="bannerImg">
        <a href="javascript:" style="display: inline;"><img src="<?= $web ?>images/datawapBanner1.jpg"></a>
        <a href="javascript:" style="display: none;"><img src="<?= $web ?>images/datawapBanner2.jpg"></a>
        <a href="javascript:" style="display: none;"><img src="<?= $web ?>images/datawapBanner3.jpg"></a>
        <a href="javascript:" style="display: none;"><img src="<?= $web ?>images/datawapBanner4.jpg"></a>
    </div>
    <section class="itemBranch">
        <div class="floatLeft Security tabContent" style="width:34%;">
            <a href="<?= \yii\helpers\Url::to(['text/baozhang']) ?>">
                <div class="horn"></div>
                <img class="floatLeft" src="<?= $web ?>images/anquan_05.png">

                <p>
                    <span>安全</span>
                    <span>保障</span>
                </p>
            </a>
        </div>
        <div class="floatLeft DataPlatform tabContent tabContentActive" style=" width:34%;">
            <a href="<?= $isGuest ? \yii\helpers\Url::to(['site/signin']) : \yii\helpers\Url::to(['text/share']) ?>">
                <div class="horn"></div>
                <img class="floatLeft" src="<?= $web ?>images/yq_07.png">

                <p>
                    <span>邀请</span>
                    <span>好友</span>
                </p>
            </a>
        </div>
        <div class="floatLeft expeGoldChange tabContent">
            <a href="<?= $isGuest ? \yii\helpers\Url::to(['site/signup']) : \yii\helpers\Url::to(['text/share']) ?>">
                <div class="horn"></div>
                <img src="<?= $web ?>images/hb_03.png">

                <p>理财本金红包</p>
            </a>
        </div>
    </section>
    <div class="breadLine">
        <p>投资金额<span>1000</span>元起，一次性还本付息</p>
    </div>

    <ul>
        <?php foreach ($experiences as $experience): ?>
            <li>
                <a class="d-block" href="<?= \yii\helpers\Url::to(['view', 'id' => $experience['id']]) ?>">
                    <div class="HomeBaoName">
                        <b class="floatLeft">[体验标]<?= mb_substr($experience->title, 0, 10, "utf-8") ?><?= mb_strlen($experience->title, "utf-8") > 10 ? '...' : '' ?></b>
                        <span class="floatRight">当日计息</span>
                        <em class="floatRight"></em>
                    </div>
                    <dl class="ModulaBox clearFloat">
                        <dd class="YearH">
                            <ul class="clearFloat wrap">
                                <li class="AnnualRevenu floatLeft">
                                    <div class="navHeight">
                                        <?php
                                        $rate = sprintf("%.1f", $experience->rate);;
                                        $rate = explode('.', $rate);
                                        ?>
                                        <b><?= $rate[0] ?>.</b><span><?= $rate[1] ?></span>
                                        <label>%</label>
                                    </div>
                                    <p class="termInves_p">年化收益率</p>
                                </li>
                                <li class=" termInvestmen floatLeft">
                                    <div class="navHeight">
                                        <b><?= $experience->invest_date ?></b>
                                        <span>月</span>
                                    </div>
                                    <p class="termInves_p">投资期限</p>
                                </li>
                                <li class="financingScal floatLeft">
                                    <div class="navHeight">
                                        <?php $moneyShort = \wanhunet\helpers\Utils::moneyShortFormat($experience->amount); ?>
                                        <b><?= $moneyShort[0] ?></b>
                                        <span style="padding-left:-4px;"><?= $moneyShort[1] ?></span>
                                    </div>
                                    <p class="termInves_p">融资规模</p>
                                </li>

                                <?php
                                $orderSum = \modules\invest\models\InvestList::getAlreadyBuy($experience);
                                $sum = $orderSum[\modules\invest\models\InvestList::STATUS_PAYED];
                                ?>

                                <?php if ($sum >= $experience->amount): ?>
                                    <li class="licBtn LiGrayOut floatRight em" id="<?= $experience->id ?>">抢光了</li>
                                <?php else: ?>
                                    <li class="licBtn LiOrange floatRight em payi" id="<?= $experience->id ?>">我要投</li>
                                <?php endif; ?>

                            </ul>
                        </dd>
                        <?php $baifenbi = intval(($sum / $experience->amount) * 100); ?>
                        <dd class="clearFloat ProBarContai">
                            <div class="floatLeft ProBarBox">
                                <div class=" ProBarinner LiGray">
                                    <p class="LiGreen" style=" width:<?= $baifenbi ?>%"></p>
                                </div>
                            </div>
                            <div class="baifenCout floatRight"><?= $baifenbi ?>%</div>
                        </dd>
                        <dd class="clearFloat ManPeop">
                            <div class="liPeoNumLeft floatLeft clearFloat">
                                <i class="floatLeft"></i>

                                <em class="floatLeft">
                                    <?= \modules\invest\models\InvestList::getAlreadyBuyCount($experience) ?>人已购买
                                </em>
                            </div>
                            <div class="LiPeoNumRight floatLeft clearFloat">
                                <i class="floatLeft"></i>
                                <em class="floatLeft">到期只能提取收益</em>
                            </div>
                        </dd>
                    </dl>
                </a>
            </li>
        <?php endforeach; ?>

        <?php foreach ($moneys as $money): ?>
            <li>
                <a class="d-block" href="<?= \yii\helpers\Url::to(['view', 'id' => $money['id']]) ?>">
                    <div class="HomeBaoName">
                        <b class="floatLeft"><?= mb_substr($money->title, 0, 10, "utf-8") ?><?= mb_strlen($money->title, "utf-8") > 10 ? '...' : '' ?></b>
                        <span class="floatRight">当日计息</span>
                        <em class="floatRight"></em>
                    </div>
                    <dl class="ModulaBox clearFloat">
                        <dd class="YearH">
                            <ul class="clearFloat wrap">
                                <li class="AnnualRevenu floatLeft">
                                    <div class="navHeight">
                                        <?php
                                        $rate = sprintf("%.1f", $money->rate);
                                        $rate = explode('.', $rate);
                                        ?>
                                        <b><?= $rate[0] ?>.</b><span><?= $rate[1] ?></span>
                                        <label>%</label>
                                    </div>
                                    <p class="termInves_p">年化收益率</p>
                                </li>
                                <li class=" termInvestmen floatLeft">
                                    <div class="navHeight">
                                        <b><?= $money->invest_date ?></b>
                                        <span>月</span>
                                    </div>
                                    <p class="termInves_p">投资期限</p>
                                </li>
                                <li class="financingScal floatLeft">
                                    <div class="navHeight">
                                        <?php $moneyShort = \wanhunet\helpers\Utils::moneyShortFormat($money->amount); ?>
                                        <b><?= $moneyShort[0] ?></b>
                                        <span style="padding-left:-4px;"><?= $moneyShort[1] ?></span>
                                    </div>
                                    <p class="termInves_p">融资规模</p>
                                </li>

                                <?php
                                $orderSum = \modules\invest\models\InvestList::getAlreadyBuy($money);
                                $sum = $orderSum[\modules\invest\models\InvestList::STATUS_PAYED];
                                ?>

                                <?php if ($sum >= $money->amount): ?>
                                    <li class="licBtn LiGrayOut floatRight mm" id="<?= $money->id ?>">抢光了</li>
                                <?php else: ?>
                                    <li class="licBtn LiOrange floatRight mm payi" id="<?= $money->id ?>">我要投</li>
                                <?php endif; ?>

                            </ul>
                        </dd>
                        <?php $baifenbi = intval(($sum / $money->amount) * 100); ?>
                        <dd class="clearFloat ProBarContai">
                            <div class="floatLeft ProBarBox">
                                <div class=" ProBarinner LiGray">
                                    <p class="LiGreen" style=" width:<?= $baifenbi ?>%"></p>
                                </div>
                            </div>
                            <div class="baifenCout floatRight"><?= $baifenbi ?>%</div>
                        </dd>
                        <dd class="clearFloat ManPeop">
                            <div class="liPeoNumLeft floatLeft clearFloat">
                                <i class="floatLeft"></i>

                                <em class="floatLeft">
                                    <?= \modules\invest\models\InvestList::getAlreadyBuyCount($money) ?>人已购买
                                </em>
                            </div>
                            <div class="LiPeoNumRight floatLeft clearFloat">
                                <i class="floatLeft"></i>
                                <em class="floatLeft">100%保本保息</em>
                            </div>
                        </dd>
                    </dl>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <a class="btn_toTop" href="javascript:scroll(0,0)" style="display: none;">返回顶部</a>

    <!--    弹出窗-->
    <section class="zhezhao" id="zhezhao2">
    </section>
    <aside class="showDiv" id="showDiv2">
        <form action="<?= \yii\helpers\Url::to(['invest/pay']) ?>" method="post" id="form">
            <div class="zhe" id="zhe2" style="display: block; height:190px;">
                <p class="ablum-edit">
                    <img id="zhe_close" width="15px;" src="<?= $web ?>images/smalldel.png" alt="">
                </p>

                <div class="clearFloat zhe-box">
                    <p class="inv-je">
                        金额：
                        <input type='number' name="investment_sum" value=""/>
                    </p>

                    <p style="font-size: 10px;">*转入当日开始计息，次日显示收益</p>

                    <div id="label_radio">
                        <label for="radio_id_1" class="label_checked" style="font-size: 12px;">账户余额支付</label>
                        <input type="radio" name="pay_style" value="balance" id="radio_id_1" checked="checked"/>
                        <br/>
                        <label for="radio_id_2" id="bank_label" style="font-size: 12px;">银行卡支付</label>
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

</div>
<script>
    $(document).ready(function () {
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
            });
        });
    });


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
</script>