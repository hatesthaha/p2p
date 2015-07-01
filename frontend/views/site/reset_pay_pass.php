<section class="login_bt" style="padding-top:20px;">

    <form id="form" action="<?= \yii\helpers\Url::to(['site/reset-pay-pass-do']) ?>" method="post">
        <div class="divoi_odi mt10">
            <div class="login_td login-fall">
                <input type="text" placeholder="请输入您的身份证号" name="idcard" value=""/>
            </div>
        </div>
        <div>
            <?= \wanhunet\helpers\Utils::csrfField() ?>
            <input style="box-shadow: #61a627 0px 5px 0px 0px; background: #6eb92b;" type="submit" value="下一步"
                   class="login_btn mt10">
        </div>
    </form>
</section>