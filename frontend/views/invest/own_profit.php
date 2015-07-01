<?php
use wanhunet\helpers\Utils;

/**
 * @var modules\invest\models\InvestList[] $eModel ,
 * @var modules\invest\models\InvestList[] $mModel ,
 * @var \modules\member\models\Member|\modules\asset\behaviors\Asset $member
 */
?>
    <section class="see-nav">
        <nav>
            <a href="javascript:void(0)" <?= \wanhunet\wanhunet::$app->request->get('t', 0) == 0 ? 'class="current"' : "" ?>
               data-cato="tyj"><span>自有投标情况</span></a>
            <a href="javascript:void(0)" data-cato="zy"><span>体验投标情况</span></a>
        </nav>
    </section>
    <section class="profit-content profit4-content" id="tyj" style="display:none;">
        <table>
            <tr>
                <td>投标金额(元)</td>
                <td>到期时间</td>
                <td>年化利率</td>
                <td>预期收益(元)</td>
            </tr>
            <?php foreach ($mModel as $m): ?>
                <tr>
                    <td><?= Utils::moneyFormat($m->investment_sum) ?></td>
                    <td><?= date('Y.m.d', $m->interest_time) ?></td>
                    <td><?= $m->invest->rate ?>%</td>
                    <td>+<?= Utils::moneyFormat($m->interest) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
    <section class="profit-content profit4-content" id="zy" style="display:none;">
        <table>
            <tr>
                <td>投标金额(元)</td>
                <td>到期时间</td>
                <td>年化利率</td>
                <td>预期收益(元)</td>
            </tr>
            <?php foreach ($eModel as $e): ?>
                <tr>
                    <td><?= Utils::moneyFormat($e->investment_sum) ?></td>
                    <td><?= date('Y.m.d', $e->interest_time) ?></td>
                    <td><?= $e->invest->rate ?>%</td>
                    <td>+<?= Utils::moneyFormat($e->interest) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

<?php $this->beginBlock('inline_scripts'); ?>

    <script>
        var a = function () {
            $(".amsincome-word").width(($(".ams-income").width() - 13) / 2);
            $(".profit-content").css("min-height", $(window).height() - $(".Y-income").height() - $(".ams-income").height() - 101);
            $(".see-nav nav a").width(($(".see-nav nav").width() - 1) / 2);
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
            catoFram[<?=\wanhunet\wanhunet::$app->request->get('t',0)?>].style.display = "block";
            subNav[<?=\wanhunet\wanhunet::$app->request->get('t',0)?>].className += " current";
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