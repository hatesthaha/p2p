<?php
$web = \wanhunet\wanhunet::getAlias('@web') . '/';
/** @var modules\invest\models\Invest[] $experiences */
/** @var modules\invest\models\Invest[] $moneys */
?>
    <style>
        .inv-box a {
            height: 105px;
            display: block;
            padding: 10px 0px 10px 0;
        }
    </style>
    <div>
        <section class="paged">
            <div class="clearFloat zhe-boxmb10">
                <img class="floatLeft" height="120px" style="margin-right:10px;" src="<?= $web ?>images/check-box.png"
                     alt="">

                <h1 class="rule-title" style="padding-top: 13px; text-align:left; font-size:18px;">玖儿小贴士：</h1>

                <h1 style="padding-top: 13px; color:#888; font-size:14px;">转入资金投资或好友投资都可以提高体验金投标上限噢~~~</h1>
            </div>
            <div class="page pt15 mt10">
                <?php foreach ($experiences as $experience): ?>
                    <aside class="inv-box">
                        <div class="clearFloat">
                            <div class="inv1">
                                <img src="<?= $web ?>images/inv.jpg" alt="投标">

                                <p>体验标</p>
                            </div>
                            <div class="inv2">
                                <p>期限：<?= $experience->invest_date ?>月</p>

                                <p>年化利率<?= $experience->rate ?>%</p>

                                <p>到期只能提取收益</p>
                                <a href="<?= \yii\helpers\Url::to(['view', 'id' => $experience['id']]) ?>"
                                   style="display: inline;">查看详情</a>
                            </div>
                            <?php
                            $orderSum = \modules\invest\models\InvestList::getAlreadyBuy($experience);
                            $sum = 0;
                            foreach ($orderSum as $p) {
                                $sum += $p;
                            }
                            ?>
                            <?php if ($sum >= $experience->amount): ?>
                                <div class="inv3 em" id="<?= $experience->id ?>">
                                    <p>
                                        已满标
                                    </p>
                                </div>
                            <?php else: ?>
                                <div class="inv3 em payi" id="<?= $experience->id ?>">
                                    <p>
                                        我要投
                                    </p>
                                </div>
                            <?php endif; ?>

                        </div>
                    </aside>
                <?php endforeach; ?>
                <?php foreach ($moneys as $money): ?>
                    <aside class="inv-box">
                        <div class="clearFloat">
                            <div class="inv1">
                                <img src="<?= $web ?>images/inv.jpg" alt="投标">

                                <p>真实标</p>
                            </div>
                            <div class="inv2">

                                <p>
                                    标题：<?= mb_substr($money->title, 0, 5, 'utf-8') ?><?= mb_strlen($money->title, 'utf-8') > 5 ? '...' : '' ?>
                                </p>

                                <p>期限：<?= $money->invest_date ?>月</p>

                                <p>年化利率<?= $money->rate ?>%</p>

                                <p>到期能提取收益和本金</p>
                                <a href="<?= \yii\helpers\Url::to(['view', 'id' => $money['id']]) ?>"
                                   style="display: inline;">查看详情</a>
                            </div>
                            <?php
                            $orderSum = \modules\invest\models\InvestList::getAlreadyBuy($money);
                            $sum = 0;
                            foreach ($orderSum as $p) {
                                $sum += $p;
                            }
                            ?>

                            <?php if ($sum >= $money->amount): ?>
                                <div class="inv3 mm" id="<?= $money->id ?>">
                                    <p>
                                        已满标
                                    </p>
                                </div>
                            <?php else: ?>
                                <div class="inv3 mm payi" id="<?= $money->id ?>">
                                    <p>
                                        我要投
                                    </p>
                                </div>
                            <?php endif; ?>

                        </div>
                    </aside>
                <?php endforeach; ?>
            </div>
        </section>
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
    </div>
<?php $this->beginBlock('inline_scripts'); ?>

    <script>
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


<?php $this->endBlock(); ?>