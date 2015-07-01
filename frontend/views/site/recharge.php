<?php
$member = \wanhunet\wanhunet::app()->member;
?>
    <section class="login_bt divoi" style="padding:20px 8px 0;" id="show1">
        <p>为了您的资金安全，银行卡持卡人姓名需与实名认证姓名一致</p>

        <form class="wh-addform" id="form" action="<?= \yii\helpers\Url::to(['site/recharge']) ?>" method="post">
            <div class="divoi_odi">
                <div class="login_td login-fall">
                    <input type="text" placeholder="请填写姓名" name="cardUserName"
                           value="<?= ($idcard_name = $member->idcard_name) ?>" <?= !empty($idcard_name) ? 'readOnly="true"' : '' ?> />
                </div>
            </div>
            <div class="divoi_odi">
                <div class="login_td login-fall">
                    <input type="text" placeholder="请填写身份证号" name="idcard"
                           value="<?= ($idcard = $member->idcard) ?>" <?= !empty($idcard) ? 'readOnly="true"' : '' ?>/>
                </div>
            </div>
            <div class="divoi_odi">
                <div class="login_td login-fall">
                    <input type="number" placeholder="请填写卡号" name="backcardid"
                           value="<?= ($bankCardId = key($member->getBankCard())) ?>" <?= !empty($bankCardId) ? 'readOnly="true"' : '' ?>/>
                </div>
            </div>
            <div class="divoi_odi">
                <div class="login_td" id="login_dt">
                    <input type="number" placeholder="请填写充值金额" name="step"/>
                </div>
                <div class="login_od wh-Whitebtn" id="login_do">
                    <span>元</span>
                </div>
            </div>
            <div class="is_agree">
                <label for="qtis_agree">
                    <input type="checkbox" id="qtis_agree" tabindex="5" value="1">
                </label>同意玖信贷
                <a href="<?= \yii\helpers\Url::to(['text/zhifu']) ?>">《移动支付协议》</a></div>

            <div class="btn-box2 clearFloat">
                <a class="wh-addbtn wh-huibtn" href="<?= \yii\helpers\Url::to(['site/setup']) ?>"
                   style="display:block">返回</a>
                <?= \wanhunet\helpers\Utils::csrfField() ?>
                <a class="wh-addbtn" href="javascript:" id="go" style="display:block">继 续</a>
            </div>
            <p class='mt10'>*单笔充值限额5万且遵循同卡进出原则。</p>

            <p>*快捷支付服务由连连支付提供。目前支持绑卡的银行有农业银行、工商银行、招商银行、中国银行、建设银行、光大银行、平安银行、浦发银行。</p>
        </form>
    </section>

    <section class="login_bt" id="show2" style="display: none;">
        <div class="divoi wh-addpage">
            <p class="font-s16 wh-addptop"><span class="cored">提示：</span>为了保障投资者资金安全，</p>

            <p>1、该支付方式限绑定1张银行卡；</p>

            <p>2、该支付方式遵循同卡进出原则，即资金只能提现至充值时使用的银行卡上，若因特殊情况需解绑，更换或注销银行卡，请在以上行为之前将投资金额进行提现处理后再进行操作；</p>

            <p class="mb10">3、如因个人原因造成充值或提现问题，玖信贷不承担任何法律责任。</p>

            <div class="btn-box2 clearFloat">
                <a class="wh-addbtn wh-huibtn" href="javascript:" id="back" style="display:block">返回修改</a>
                <a class="wh-addbtn" href="javascript:" id="pay" style="display:block">继 续</a>
            </div>
        </div>

    </section>



<?php $this->beginBlock('inline_scripts'); ?>
    <script>
        $(document).ready(function () {
            var go = function () {
                <?php if(empty($bankCardId)):?>
                $("#show2").show();
                $("#show1").hide();
                <?php else: ?>
                $("#form").submit();
                <?php endif;?>
            };
            var alertY = function () {
                alertText('请同意玖信贷《移动支付协议》');
            };
            $('#pay').click(function () {
                if ($.trim($("input[name='step']").val()) === '') {
                    alertText('请填入金额');
                    return false;

                }
                $("#form").submit();
            });
            $("#back").click(function () {
                $("#show1").show();
                $("#show2").hide();
            });
            $('#go').bind('click', alertY);
            $("#qtis_agree").change(function () {
                var goclick = $('#go').attr('click');
                if (goclick == 1) {
                    $('#go').unbind('click', go);
                    $('#go').bind('click', alertY);
                    $('#go').attr('click', '0');
                } else {
                    $('#go').bind('click', go);
                    $('#go').unbind('click', alertY);
                    $('#go').attr('click', '1');
                }
            });
        });
    </script>
<?php $this->endBlock(); ?>