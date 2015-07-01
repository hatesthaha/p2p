<?php
$member = \wanhunet\wanhunet::app()->member;
?>
<section class="login_bt">
    <div class="divoi wh-addpage">
        <p class="font-s16 wh-addptop">可用余额：<span
                class="cored"><?= $member->getMoney() ?></span> 元</p>

        <form class="wh-addform" id="form" action="<?= \yii\helpers\Url::to(['site/recharge']) ?>" method="post"
              style="padding: 8px 0;">
            <div class="divoi_odi">
                <div class="login_od wh-greenbtn">
                    <a href="#" id="getCaptcha" style="display:block" disabled="true">充值金额</a>
                </div>
                <div class="login_td" style="border-radius:0; padding:10px;">
                    <input type="number" placeholder="请填写充值金额" name="step"/>
                </div>
                <div class="login_od wh-Whitebtn" id="login_do">
                    <a href="#" id="getCaptcha" style="display:block" disabled="true">元</a>
                </div>
            </div>
            <div>
                <input type="hidden" name="cardUserName"
                       value="<?= ($idcard_name = $member->idcard_name) ?>" <?= !empty($idcard_name) ? 'readOnly="true"' : '' ?> />
                <input type="hidden" name="idcard"
                       value="<?= ($idcard = $member->idcard) ?>" <?= !empty($idcard) ? 'readOnly="true"' : '' ?>/>
                <input type="hidden" name="backcardid"
                       value="<?= ($bankCardId = key($member->getBankCard())) ?>" <?= !empty($bankCardId) ? 'readOnly="true"' : '' ?>/>
                <?= \wanhunet\helpers\Utils::csrfField() ?>
                <input style="box-shadow: #61a627 0px 5px 0px 0px; background: #6eb92b;" type="submit" value="确  认"
                       class="login_btn mt10" id="pay">
            </div>
        </form>
    </div>
</section>
<script>
    $(document).ready(function () {
        $('#pay').click(function () {
            if ($.trim($("input[name='step']").val()) === '') {
                alertText('请填入金额');
                return false;

            }
            $("#form").submit();
        });
    });
</script>