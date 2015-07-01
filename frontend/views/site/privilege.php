<?php
$member = \wanhunet\wanhunet::app()->member;
$root = \wanhunet\wanhunet::getAlias('@web') . '/';
/**
 * @var $myInveset
 * @var $fInveset
 * @var modules\asset\models\AssetMoney[] $emMaxList
 * @var modules\invest\models\InvestList[] $einvestList
 * @var $einvestListSum
 */
?>
    <section class="see-nav">
        <nav class="clearFloat">
            <a href="javascript:void(0)" class="current" data-cato="tyj"><span>体验金(元)</span></a>
            <a href="javascript:void(0)" data-cato="zy"><span>体验金投标上限（元）</span></a>
            <a href="javascript:void(0)" data-cato="sy"><span>体验金投标收益（元）</span></a>
        </nav>
    </section>
    <section class="pr-jx" id="tyj" style="display:none;">
        <aside style="display: block;">
            <div class="pvi_di">
                <div class="pvi_di_di">
                    <p id="ppp">
                        <span>￥</span><?= sprintf("%.2f", substr(sprintf("%.3f", $member->getExperienceMoney()), 0, -2)) ?>
                    </p>
                </div>
                <div class="pvi_di_th">
                    <a href="<?=\yii\helpers\Url::to(['text/tiyanjinguize'])?>">查看体验金规则 &gt;</a>
                </div>
            </div>
            <?php foreach ($member->getExperienceMoneys() as $key => $em): ?>
                <div class="pvi_th">
                    <div class="pvi_th_od">
                        <div class="pvi_th_odo">
                            <p>
                                <?= $em->action_uid == $member->id ? '' : '好友' ?>
                                <?= $em->action_uid == $member->id ? '' : $em->actionUid->getPhone() ?>
                                <?= $em->getActionName() ?>
                            </p>
                        </div>
                        <div class="pvi_th_di">
                            <div class="pvi_th_dio">
                                <p>体验金</p>

                                <p>+<?= $em->step ?>元</p>
                            </div>
                            <div class="pvi_th_diodi">
                                <div class="pvi_th_diodio">
                                    <p>发放时间:<span><?= date("Y-m-d", $em->created_at) ?></span></p>
                                </div>
                                <div class="pvi_th_diodio">
                                    <p>过期时间:<span><?= date("Y-m-d", $em->created_at + (30 * 24 * 3600)) ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </aside>
        <div style="height:50px;"></div>
        <div class="pvi_fo">
            <a href="<?=\yii\helpers\Url::to(['text/tiyanjishangxian'])?>" class="pvi_fo_od" style="border-right:1px solid #ccc;">
                <p>提高体验金投标上限</p>
            </a>
            <a href="#" class="pvi_fo_od pvi_fo_rr">
                <p>分享增加体验金</p>
            </a>
        </div>
    </section>

    <section class="pr-jx" id="zy" style="display:none; padding:0;">
        <div class="pvi_di">
            <div class="pvi_di_di">
                <p id="ppp">
                    <span>￥</span><?= \wanhunet\helpers\Utils::moneyFormat($member->getExperienceMoneyMax()) ?>
                </p>
            </div>
            <div class="pvi_di_tth">
                <p class="clearFloat"><span
                        class="floatLeft">好友投资总额：<?= \wanhunet\helpers\Utils::moneyFormat($fInveset) ?></span>
                    <span class="floatRight">个人投资总额：<?= \wanhunet\helpers\Utils::moneyFormat($myInveset) ?></span></p>
            </div>
            <div class="pvi_di_th">
                <a href="<?=\yii\helpers\Url::to(['text/tiyanjinguize'])?>">查看体验金规则 &gt;</a>
            </div>
        </div>
        <aside class="profit-content profit3-content profit-teshu">
            <table>
                <?php foreach ($emMaxList as $vo): ?>
                    <tr>
                        <td><?= date('m.d', $vo->created_at) ?></td>
                        <td>
                            <?= $vo->user_id == $vo->action_uid ? '' : '好友' ?>
                            <?= $vo->actionUid->getPhone() ?>
                            投资成功
                        </td>
                        <td>入账+<?= $vo->step ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </aside>
        <div style="height:50px;"></div>
        <div class="pvi_fo">
            <a href="<?=\yii\helpers\Url::to(['text/tiyanjishangxian'])?>" class="pvi_fo_od" style="border-right:1px solid #ccc;">
                <p>提高体验金投标上限</p>
            </a>
            <a href="#" class="pvi_fo_od pvi_fo_rr">
                <p>分享增加体验金</p>
            </a>
        </div>
    </section>
    <section class="pr-jx" id="sy" style="display:none;">
        <div class="pvi_di">
            <div class="pvi_di_di">
                <p id="ppp"><span>￥</span><?= \wanhunet\helpers\Utils::moneyFormat($einvestListSum) ?></p>
            </div>
            <div class="pvi_di_th">
                <a href="<?=\yii\helpers\Url::to(['text/tiyanjinguize'])?>">查看体验金规则 &gt;</a>
            </div>
        </div>
        <aside class=" profit-content">
            <table>
                <?php foreach ($einvestList as $invest): ?>
                    <tr>
                        <td><?= $invest->invest->title ?></td>
                        <td>+<?= \wanhunet\helpers\Utils::moneyFormat($invest->interest) ?>(元)</td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </aside>
        <div style="height:50px;"></div>
        <div class="pvi_fo">
            <a href="<?=\yii\helpers\Url::to(['text/tiyanjishangxian'])?>" class="pvi_fo_od" style="border-right:1px solid #ccc;">
                <p>提高体验金投标上限</p>
            </a>
            <a href="#" class="pvi_fo_od pvi_fo_rr">
                <p>分享增加体验金</p>
            </a>
        </div>
    </section>
    <section class="zhezhao" id="zhezhao2">
    </section>
    <aside class="showDiv" id="showDiv2">
        <div class="zhe" id="zhe2" style="display: block;">
            <p class="ablum-edit">
                <img id="zhe_close" width="15px;" src="<?=$root?>images/smalldel.png" alt="">
            </p>

            <div class="clearFloat zhe-box">
                <img class="floatLeft" height="100px" src="<?=$root?>images/check-box.png" alt="">

                <h1>点击右上角的按钮分享，</h1>

                <h1>好友注册成功即可获得体验金噢！</h1>
            </div>
            <span id="bto2">确定</span>
        </div>
    </aside>
<?php $this->beginBlock('inline_scripts'); ?>
    <script>
        var a = function () {
            $(".amsincome-word").width(($(".ams-income").width() - 13) / 2);
            $(".profit-content").css("min-height", $(window).height() - $(".Y-income").height() - $(".ams-income").height() - 191);
            $(".see-nav nav a").width(($(".see-nav nav").width() - 2) / 3);
        };
        $(document).ready(function () {
            a();
            window.onresize = function () {
                a();
            };
            var UA = window.navigator.userAgent;  //使用设备
            var CLICK = "click";
            if (/ipad|iphone|android/.test(UA)) {   //判断使用设备
                CLICK = "tap";
            }
            var catoFram = $(".pr-jx");
            var subNav = $(".see-nav nav a");
            catoFram[0].style.display = "block";
            subNav[0].className += " current";
            subNav[CLICK](function () {
                var _this = $(this);
                var id = _this.data("cato");
                var cur = $("#" + id);
                subNav.removeClass("current");
                _this.addClass("current");
                catoFram.hide();
                cur.scrollTop(0);
                cur.show();
            });
            $(".pvi_fo_rr")[CLICK](function () {
                event.preventDefault();
                $("#zhezhao2").css("display", "block");
                $("#showDiv2").css("display", "block");

            });
            $("#zhe_close,#bto2")[CLICK](function () {
                event.preventDefault();
                $("#zhezhao2").css("display", "none");
                $("#showDiv2").css("display", "none");
            });
            $("#bto2")[CLICK](function () {
                event.preventDefault();
                console.log(123);
            });
        });
    </script>
<?php $this->endBlock(); ?>