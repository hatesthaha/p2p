<section class="clearFloat Y-income">
    <div class="floatLeft Yincome-word">
        <p>可取收益（元）</p>

        <p>￥<big><?= \wanhunet\helpers\Utils::moneyFormat($keshou) ?></big></p>
    </div>
    <div class="floatRight">
        <p><a href="<?= \yii\helpers\Url::to(['see-profite']) ?>">体验投标收益 ></a></p>
    </div>
</section>
<section class="clearFloat ams-income">
    <div class="floatLeft amsincome-word" style="text-align:center;">
        <p>累计总收益（元）</p>

        <p><?= \wanhunet\helpers\Utils::moneyFormat($leiji + $yesterdayshou) ?></p>
    </div>
    <span class="floatLeft line"></span>

    <div class="floatRight amsincome-word linel1" style="text-align:center;">
        <p>昨日总收益（元）</p>

        <p><?= \wanhunet\helpers\Utils::moneyFormat($yesterdayshou) ?></p>
    </div>
</section>
<aside class='p10' style="text-align:right;">
<!--    <p><a href="--><?//= \yii\helpers\Url::to(['invest/fund']) ?><!--">查看资金流水</a></p>-->
</aside>
<section class="profit-content">
    <table>
        <?php foreach ($list as $key => $vo): ?>
            <tr>
                <td><?= date('Y.m.d', $key) ?></td>
                <td>+<?= \wanhunet\helpers\Utils::moneyFormat($vo) ?></td>
            </tr>
        <?php endforeach; ?>

    </table>
</section>
<script>
    var a = function () {
        $(".amsincome-word").width(($(".ams-income").width() - 13) / 2);
        $(".profit-content").css("min-height", $(window).height() - $(".Y-income").height() - $(".ams-income").height() - 101);
    };
    $(document).ready(function () {
        a();
        window.onresize = function () {
            a();
        };
    });
</script>
