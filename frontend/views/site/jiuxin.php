<?php /** @var \wanhunet\base\View $this */ ?>
<section class="login_bt" style="padding-top:20px;">

    <form id="form" action="<?= \yii\helpers\Url::to(['site/setjiuxin']) ?>" method="post">
        <div class="divoi_odi mt10">
            <div class="login_td login-fall">
                <input type="text" placeholder="请填写玖信贷账号" name="jiuxinusername" value="<?= $this->getParamsErrors('jiuxinusername') ?>" />
            </div>
        </div>
        <div class="divoi_odi mt10">
            <div class="login_td login-fall">
                <input type="password" placeholder="请填写玖信贷账号密码" name="jiuxinpwd" value="<?= $this->getParamsErrors('jiuxinpwd') ?>" />
            </div>
        </div>
        <div>
            <?= \wanhunet\helpers\Utils::csrfField() ?>
            <input style="box-shadow: #61a627 0px 5px 0px 0px; background: #6eb92b;" type="submit" value="确认绑定"
                   class="login_btn mt10" id="bt">
        </div>
        <div class="divoi">
            <p style="text-align:right;">还没有账号？ 马上去<a href="http://www.jiuxindai.com/web_user/user/register_show" style="color:#0096e0;">注册</a></p>
        </div>
    </form>
</section>
