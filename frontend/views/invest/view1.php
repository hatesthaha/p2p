<?php
/** @var $invest */
?>
<style>
    .shezhi_div_tddv {
        height: auto;
        position: relative;
    }

    .shezhi_div_tddvt {
        width: 60px;
        display: block;
        -webkit-box-flex: 0;
    }

    .shezhi_div_tddvf {
        display: -webkit-box;
        -webkit-box-flex: 1;
        text-align: right;
    }
    #xiangqing_wanhu div{
        display: inline-block;
        width: 100%;
    }
</style>
<div style="height: 2px;"></div>
<div class="box" style="margin-top: 5px;">
    <section>
        <div id="inf-ts">
            <p class="inf-title">投资标信息</p>
            <aside class="shezhi_div_td">
                <div class="shezhi_div_tdv">
                    <div class="shezhi_div_tddv">
                        <div class="shezhi_div_tddvt" id="eye-titleh">
                            <p id="eye-title">标题</p>
                        </div>
                        <div class="shezhi_div_tddvf" style="text-align:left;">
                            <p style="width:100%;"><span style="display: inline-block;"><?= $invest['title'] ?></span>
                            </p>
                        </div>
                    </div>
                    <div class="shezhi_div_tddv">
                        <div class="shezhi_div_tddvt">
                            <p>最低金额</p>
                        </div>
                        <div class="shezhi_div_tddvf">
                            <p style="width:100%;"><span><?= $invest['each_min'] ?>(元)</span></p>
                        </div>
                    </div>
                    <div class="shezhi_div_tddv">
                        <div class="shezhi_div_tddvt">
                            <p style="width:100%;">期限</p>
                        </div>
                        <div class="shezhi_div_tddvf">
                            <p style="width:100%;"><span><?= $invest['invest_date'] ?>(月)</span></p>
                        </div>
                    </div>
                    <div class="shezhi_div_tddv">
                        <div class="shezhi_div_tddvt">
                            <p>年化利息</p>
                        </div>
                        <div class="shezhi_div_tddvf">
                            <p style="width:100%;"><span><?= $invest['rate'] ?>%</span></p>
                        </div>
                    </div>
                    <div class="shezhi_div_tddv">
                        <div class="shezhi_div_tddvt">
                            <p>剩余金额</p>
                        </div>
                        <div class="shezhi_div_tddvf">
                            <p style="width:100%;">
                                <span><?= $invest['amount'] - ($invest['orderSum']['10'] + $invest['orderSum']['20']) ?>
                                    (元)</span>
                            </p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
        <div id="inf-ts1">
            <p class="inf-title click-title">项目详情</p>
            <aside class="shezhi_div_td" style="display: none;">
                <div class="shezhi_div_tdv">
                    <div class="shezhi_div_tddv" id="xiangqing_wanhu" style="width: 100%">
                        <?= $invest['introduce'] ?>
                    </div>
                </div>
            </aside>
        </div>
        <div id="inf-ts3">
            <p class="inf-title click-title">相关照片</p>
            <aside class="shezhi_div_td" style="display: none;">
                <div class="shezhi_div_tdv">
                    <p>
                        <?php
                        $imgs = json_decode($invest['imgs']);
                        ?>
                        <?php foreach ($imgs as $img): ?>
                            <img src="<?= \wanhunet\wanhunet::getAlias('@web') ?>/upload/<?= $img ?>" alt=""
                                 style="max-width: 100%; height: auto;"/>
                        <?php endforeach; ?>
                    </p>
                </div>
            </aside>
        </div>

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

                        <p>*转入当日开始计息，次日显示收益</p>

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
                    <span id="bto2">确定支付</span>
                </div>
            </form>
        </aside>
        <div style="position: fixed;top: 1px;right: 12px;">
            <style>
                .inv3 p{
                    font-size: 12px;
                    background: white;
                }
            </style>
            <?php
            $sum = $invest['amount'] - ($invest['orderSum']['10'] + $invest['orderSum']['20']);
            ?>
            <?php if ($sum <= 0): ?>
                <div
                    class="inv3 <?= $invest['type'] == \modules\invest\models\Invest::TYPE_EXPERIENCE_MONEY ? 'em' : 'mm' ?>"
                    id="<?= \wanhunet\wanhunet::$app->request->get('id') ?>">
                    <p>
                        已满标
                    </p>
                </div>
            <?php else: ?>
                <div
                    class="inv3 <?= $invest['type'] == \modules\invest\models\Invest::TYPE_EXPERIENCE_MONEY ? 'em' : 'mm' ?> payi"
                    id="<?= \wanhunet\wanhunet::$app->request->get('id') ?>">
                    <p>
                        我要投
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>
<script>
    $(document).ready(function () {
        console.log($("#eye-titleh").height());
        $("#eye-title").css("height", $("#eye-titleh").height());
        $(".click-title").click(function () {
            $(this).next('aside').toggle();
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