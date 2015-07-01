<section class="login_bt" style="padding-top:20px;">
    <form id="form" action="<?= \yii\helpers\Url::to(['wechat/login']) ?>" method="post">
        <div class="divoi_odi">
            <div class="login_od" style="">
                <a href="javascript:" id="getCaptcha" style="display:block" disabled="true">+86</a>
            </div>
            <div class="login_td">
                <input type="number" placeholder="请输入手机号码" name="username"
                       value="<?= $this->getParamsErrors('phone', '') ?>"/>
            </div>

        </div>
        <div class="divoi_odi">
            <div class="login_td login-fall">
                <input type="password" placeholder="请输入登录密码" name="password"/>
            </div>
        </div>
        <div class="login_d"></div>
        <div>
            <?= \wanhunet\helpers\Utils::csrfField() ?>
            <input type="hidden" name="open_id" value="<?= \wanhunet\wanhunet::$app->request->get('open_id') ?>"/>
            <input style="box-shadow: #61a627 0px 5px 0px 0px; background: #6eb92b;" type="submit" value="开启免登录模式"
                   class="login_btn">
        </div>
    </form>
</section>