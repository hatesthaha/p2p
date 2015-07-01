<?php
$member = \wanhunet\wanhunet::app()->member;
?>
<section class="login_bt">
    <div class="divoi wh-addpage">
        <p class="font-s16 wh-addptop">可用余额：<span class="cored"><?= $member->getMoney() ?></span> 元</p>

        <form id="form" action="<?= \yii\helpers\Url::to(['invest/mention-post']) ?>" method="post">
            <div class="divoi_odi" style="padding-bottom:10px;">
                <div class="login_td" id="login_dt">
                    <input style="text-align:right;" type="number" name="step" placeholder="请填写提现金额 "/>
                </div>
                <div class="login_od wh-Whitebtn" id="login_do">
                    <a href="javascript:" style="display:block" disabled="true">元</a>
                </div>
            </div>
            <p class="font-s16 hui8 mb10" style="text-align:right;">
                可用金额<?= $member->getMoney() ?>元</p>

            <p class="font-s16 wh-addptop mt20 mb10">提现银行卡：</p>

            <div class="card-info-box">
                <div class="card-info">
                    <?php
                    $bankCard = $member->getBankCard();
                    if (count($bankCard) > 0):
                        $bankCard = current($bankCard);
                        /** @var \modules\asset\models\BankCard $bankCard */
                        ?>
                        <p class="font-s16">开户名：<?= $member->idcard_name ?></p>
                        <p class="font-s16">银行卡：<?= $bankCard->getCardId() ?></p>
                    <?php endif; ?>

                    <!--                    <p class="wh-chcard"><a href="#">[点击修改银行卡]</a></p>-->
                </div>
                <!--                <p class="wh-addcard mt10"><a href="#">添加银行卡</a></p>-->
            </div>
            <div style="margin:50px 0;">
                <?= \wanhunet\helpers\Utils::csrfField() ?>
                <a class="wh-addbtn" href="javascript:" id="submit" style="display:block">确 定</a>
            </div>
        </form>
    </div>
</section>
<?php $this->beginBlock('inline_scripts'); ?>
<script>
    $(document).ready(function () {
        $('#submit').click(function () {
            $("#form").submit();
        });
    });
</script>
<?php $this->endBlock(); ?>
