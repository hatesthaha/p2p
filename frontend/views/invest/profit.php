<?php
$web = \wanhunet\wanhunet::getAlias('@web') . '/';
$member = \wanhunet\wanhunet::app()->member;
/** @var modules\asset\models\AssetMoney[] $assetMoneyList */
/** @var modules\invest\models\InvestList[] $investList */
?>
    <section class="see-nav">
        <nav>
            <a href="javascript:void(0)" class="current" data-cato="tyj"><span>可用余额(元)</span></a>
            <a href="javascript:void(0)" data-cato="zy"><span>账户明细</span></a>
            <a href="javascript:void(0)" data-cato="sy"><span>已投金额(元)</span></a>
        </nav>
    </section>
    <section class="profit-content prr" id="tyj" style="display:none; background:#f7f7f6;">
        <p class="have-money">￥<big><?= $member->getMoney() ?></big></p>
        <a class="have-btn" href="<?= \yii\helpers\Url::to(['invest/list']) ?>">马上去投资</a>

        <div class="poo" style="bottom:8px; right:15px; width:100%">
            <img height="197px" class="mcenter" src="<?= $web ?>images/ts-word.png" alt="温馨提示">

            <div>
    </section>
    <section class="profit-content profit3-content" id="zy" style="display:none;">
        <?php if (!empty($assetMoneyList)): ?>
            <table>
                <tr>
                    <td>时间</td>
                    <td style="width: 10%;">类型</td>
                    <td>金额</td>
                    <td style="width: 33%;">备注</td>
                </tr>
                <?php foreach ($assetMoneyList as $assetMoney): ?>
                    <tr>
                        <td><?= date("Y.m.d", $assetMoney->updated_at) ?></td>
                        <td style="width: 10%;"><?= $assetMoney->getActionName() ?></td>
                        <td>¥<?= $assetMoney->getStep() ?></td>
                        <td style="width: 33%;"><?= isset(json_decode($assetMoney->llinfo)['0']) ? json_decode($assetMoney->llinfo)['0'] : ''; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </section>
    <section class="profit-content profit3-content" id="sy" style="display:none;">
        <table>
            <tr>
                <td>时间</td>
                <td>标名</td>
                <td>金额</td>
            </tr>
            <?php foreach ($investList as $invest): ?>
                <tr>
                    <td><?= date("Y.m.d", $invest->pay_time) ?></td>
                    <td><?= $invest->invest->title ?></td>
                    <td>¥<?= \wanhunet\helpers\Utils::moneyFormat($invest->investment_sum) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
<?php $this->beginBlock('inline_scripts'); ?>

    <script>
        var a = function () {
            $(".amsincome-word").width(($(".ams-income").width() - 13) / 2);
            $(".profit-content").css("min-height", $(window).height() - $(".Y-income").height() - $(".ams-income").height() - 120);
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
            var catoFram = $(".profit-content");
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
        });
    </script>
<?php $this->endBlock(); ?>