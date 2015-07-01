<?php
use \wanhunet\helpers\Utils;

$root = \wanhunet\wanhunet::getAlias('@web');
$member = \wanhunet\wanhunet::app()->member;
/**
 * @var $sum
 * @var $earnSum
 * @var $yesterdayEarnIng
 */
/**
 * @var $esum
 * @var $esum
 * @var $eearnSum
 * @var $eyesterdayEarnIng
 */
?>
    <header class="clearFloat index-top" style="line-height:40px; background:#fff;">
        <div class="floatLeft">
            <img height="40px" src="<?= $root ?>/images/logo.png" alt="标志">
        </div>
        <div class="floatRight">
            <p><a href="<?= \yii\helpers\Url::to(['site/setup']) ?>"> 我的帐户 &gt;</a></p>
        </div>
    </header>
    <section class="clearFloat Y-income">
        <!--        <a href="--><? //= \yii\helpers\Url::to(['invest/see-profitm']) ?><!--">-->
        <div href="javascript:">
            <div class="floatLeft Yincome-word">
                <p>昨日收益</p>
                <?php
                ?>
                <p>￥<big><?= Utils::moneyFormat($yesterdayEarnIng + $eyesterdayEarnIng) ?></big></p>
            </div>
        </div>

        <div class="floatRight">
            <a class="d-block" href="tel:122354435">
                <img height="71px" src="<?= $root ?>/images/kf-phone.png" alt="客户电话"/>
            </a>
        </div>
    </section>
    <section class="clearFloat ams-income">
        <div class="floatLeft amsincome-word">
            <p>累计总收益</p>

            <p>￥<big><?= Utils::moneyFormat($earnSum + $eearnSum) ?></big></p>
        </div>
        <span class="floatLeft line"></span>
        <a href="<?= \yii\helpers\Url::to(['site/recharge']) ?>" style="color: #000000">
            <div class="floatRight amsincome-word linel1">
                <p>我的资产</p>

                <p>￥<big><?= Utils::moneyFormat($sum + $member->getMoney()) ?></big></p>
            </div>
        </a>
    </section>




    <section class='p10'>
        <ul class="p-Digitaldiv clearFloat">
            <li>
                <p class="p-Digitaltitle">体验金</p>

                <div class="prr d-block p-Digitalbox" href="javascript:;">
                    <img
                        src="<?= $root ?>/images/quan/<?php
                        $memberExpMMax = $member->getExperienceMoneyMax();
                        if (($wh_em = intval($member->getExperienceMoney())) == 0) {
                            $max2em = 0;
                        } else {
                            $max2em = intval(($wh_em / $memberExpMMax) * 100);
                        }
                        if (intval($memberExpMMax) < $wh_em) {
                            $max2em = 100;
                        }
                        echo intval($max2em); ?>.png"
                        alt="">
                    <span class="poo p-Digital">￥<?= Utils::moneyFormat($member->getExperienceMoney()) ?></span>
                </div>
                <a class="p-Digitalbtn" href="javascript:;" id="ti3"><span>提升</span></a>
            </li>
            <li>
                <p class="p-Digitaltitle">体验金上限</p>

                <div class="prr d-block p-Digitalbox" href="javascript:;">
                    <img src="<?= $root ?>/images/quan/100.png" alt="">
                    <span class="poo p-Digital">￥<?= Utils::moneyFormat($member->getExperienceMoneyMax()) ?></span>
                </div>
                <a class="p-Digitalbtn" href="javascript:;" id="ti2"><span>提升</span></a>
            </li>
        </ul>
    </section>
    <aside class='p10'>
        <p>*自己投资或好友投资都可以提高上限</p>
    </aside>

    <section class="table-income">
        <a href="<?= \yii\helpers\Url::to(['invest/own-profit']) ?>" style="color: #ffffff">
            <table>
                <tr>
                    <td>项目</td>
                    <td>投标额</td>
                    <td>收益（元）</td>
                </tr>
                <tr>
                    <td>自有投标</td>
                    <td><?= $sum ?></td>
                    <td><?= Utils::moneyFormat($earnSum) ?></td>
                </tr>
                <tr>
                    <td>体验投标</td>
                    <td><?= Utils::moneyFormat($esum) ?></td>
                    <td><?= Utils::moneyFormat($eearnSum) ?></td>
                </tr>
            </table>
        </a>
    </section>
    <aside class='p10'>
        <p>*玖信理财承诺不会泄露您的个人信息*</p>
    </aside>

    <footer class="ind-footer" style="padding-bottom:40px;">
        <aside class="fot">
            <ul>
                <li><a href="<?= \yii\helpers\Url::to(['text/about']) ?>">关于玖信 </a></li>
                <li><a href="javascript:">|</a></li>
                <li><a href="<?= \yii\helpers\Url::to(['text/baozhang']) ?>"> 玖信保障</a></li>
                <li><a href="javascript:">|</a></li>
                <li><a href="<?= \yii\helpers\Url::to(['text/problem']) ?>">常见问题</a></li>
                <li><a href="javascript:">|</a></li>
                <li><a href="<?= \yii\helpers\Url::to(['site/main']) ?>">品牌首页</a></li>
            </ul>
        </aside>
    </footer>
    <nav class="index-nav clearFloat" style="position:fixed;bottom:0;right:0; width:100%;">
        <a href="<?= \yii\helpers\Url::to(['invest/withdraw']) ?>">提现</a>
        <a href="<?= \yii\helpers\Url::to(['invest/profit']) ?>">投标</a>
    </nav>
    <section class="zhezhao" id="zhezhao2">
    </section>

    <aside class="showDiv" id="showDiv3">
        <div class="zhe" id="zhe3" style="display: block;">
            <p class="ablum-edit">
                <img class="zhe_close" id="close_3" width="15px;" src="<?= $root ?>/images/smalldel.png" alt="">
            </p>

            <div class="clearFloat zhe-box">
                <img class="floatLeft" height="100px" src="<?= $root ?>/images/check-box.png" alt="">

                <h1>点击右上角的按钮分享，</h1>

                <h1>好友注册成功即可获得体验金噢！</h1>
            </div>
            <span class="bto2" id="bto_3">确定</span>
        </div>
    </aside>

    <aside class="showDiv" id="showDiv2">
        <div class="zhe" id="zhe2" style="display: block;height: 278px;">
            <p class="ablum-edit">
                <img class="zhe_close" id="close_2" width="15px;" src="<?= $root ?>/images/smalldel.png" alt="">
            </p>

            <div class="clearFloat zhe-box">
                <img style="padding: 0px 13px 8px 0px;" class="floatLeft" height="100px"
                     src="<?= $root ?>/images/check-box.png" alt="">

                <h1 style="font-weight: 500;">如何获取体验金和体验金上限？</h1>

                <p style="margin-top:5px;display: block;">1、用户可以通过手机认证、身份认证、邮箱认证、绑定玖信贷账号、支付账户开通获得体验金并且提升体验金上限</p>

                <p style="margin-top:5px;display: block;">2、通过分享玖信理财给好友获得体验金和提升体验金上限</p>

                <p style="margin-top:5px;display: block;">3、通过投真实标提升体验金上限 </p>
            </div>
            <span class="bto2" id="bto_2">确定</span>
        </div>
    </aside>

<?php $this->beginBlock('inline_scripts'); ?>
    <script>
        var a = function () {
            $(".amsincome-word").width(($(".ams-income").width() - 13) / 2);
            $(".p-Digitaldiv li").width(($(".p-Digitaldiv").width() - 10) / 2);
            $(".p-Digitalbox").width(($(".p-Digitaldiv").width() - 10) / 2);
            $(".index-nav a").width(($(".index-nav").width() - 1) / 2);
        };
        $(document).ready(function () {
            a();
            window.onresize = function () {
                a();
            };
            var UA = window.navigator.userAgent; //使用设备
            var CLICK = "click";
            if (/ipad|iphone|android/.test(UA)) { //判断使用设备
                CLICK = "tap";
            }
            $(".p-Digitalbtn")[CLICK](function () {
                event.preventDefault();
                var id = $(this).attr("id").replace('ti', '');

                $("#zhezhao2").css("display", "block");
                $("#showDiv" + id).css("display", "block");

            });
            $(".zhe_close,.bto2")[CLICK](function () {
                event.preventDefault();
                var id = $(this).attr("id").replace('close_', '').replace('bto_', '');
                $("#zhezhao2").css("display", "none");
                $("#showDiv" + id).css("display", "none");
            });
        });
    </script>
<?php $this->endBlock(); ?>