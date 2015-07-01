<?php /** @var \wanhunet\base\View $this */ ?>
<section class="login_bt" style="padding-top:20px;">
    <form id="form" action="<?= \yii\helpers\Url::to(['site/idcard']) ?>" method="post">
        <div class="divoi_odi">
            <div class="login_td login-fall">
                <input type="text" placeholder="请填写身份证号" name="idcard"
                       value="<?= $this->getParamsErrors('idcard', '') ?>"/>
            </div>
        </div>
        <div class="divoi_odi">
            <div class="login_td login-fall">
                <input type="text" placeholder="请填写真实姓名" name="idcard_name"
                       value="<?= $this->getParamsErrors('idcard_name', '') ?>"/>
            </div>
        </div>
        <div class="login_d"></div>
        <div>
            <?= \wanhunet\helpers\Utils::csrfField() ?>
            <input style="box-shadow: #61a627 0px 5px 0px 0px; background: #6eb92b;" type="submit" value="确认认证"
                   class="login_btn">
        </div>
    </form>
</section>