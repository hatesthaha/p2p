<?php
/** @var \wanhunet\base\View $this */
$button = $this->getParamsErrors('min', null);
$button = $button !== null ? $button . '秒' : '获取验证码';
?>
    <section class="login_bt" style="padding-top:20px;">
        <form id="form" action="<?= \yii\helpers\Url::to(['site/email']) ?>" method="post">
            <div class="divoi_odi mt10">
                <div class="login_td login-fall">
                    <input type="email" placeholder="请填写常用邮箱账号" name="email" id="email"
                           value="<?= $this->getParamsErrors('email', '') ?>"/>
                </div>
            </div>
            <div class="divoi_odi">
                <div class="login_td" id="login_dt">
                    <input type="text" placeholder="请输入验证码" name="captcha"/>
                </div>
                <div class="login_od" id="login_do">
                    <a href="javascript:" id="getCaptcha" style="display:block"><?= $button ?></a>
                    <?= \wanhunet\helpers\Utils::csrfField() ?>
                </div>
            </div>
            <div>
                <input style="box-shadow: #61a627 0px 5px 0px 0px; background: #6eb92b;" type="submit" value="确认认证"
                       class="login_btn mt10" id="bt">
            </div>
        </form>
        <form action="<?= \yii\helpers\Url::to(['site/email-captcha']) ?>" method="post" id="captchaForm">
            <input name="email" id="cemail" type="hidden" value="<?= $this->getParamsErrors('email', '') ?>"/>
            <?= \wanhunet\helpers\Utils::csrfField() ?>
        </form>
    </section>
<?php $this->beginBlock('inline_scripts'); ?>
    <script>
        $(document).ready(function () {
            var c = <?=$this->getParamsErrors('min', '60')?>;
            var t;
            var timedCount = function () {
                $("#getCaptcha").text(c + '秒');
                c = c - 1;
                if (c === 0 || c <= 0) {
                    clearTimeout(t);
                    $("#getCaptcha").text('获取验证码');
                } else {
                    t = setTimeout(timedCount, 1000);
                }
            };
            if (<?= $this->getParamsErrors('min', 'false') ?>) {
                timedCount();
            }


            $("#getCaptcha").click(function () {
                $("#cemail").val($("#email").val());
                $("#captchaForm").submit();
            });
        });
    </script>
<?php $this->endBlock(); ?>