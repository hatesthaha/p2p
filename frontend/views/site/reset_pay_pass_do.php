<?php
$member = \wanhunet\wanhunet::app()->member;
?>
    <section class="login_bt">
        <div class="divoi">
            <p>我们已发送短信验证码至<?= $member->getPhone() ?>，请在输入框内填写验证码，若未收到请耐心等候~</p>
        </div>

        <form id="form" action="<?= \yii\helpers\Url::to(['site/reset-pay-pass-do-post']) ?>" method="post">
            <div class="divoi_odi">
                <div class="login_od" style="">
                    <a href="javascript:" id="getCaptcha" style="display:block"
                       disabled="true"><?= empty($time) ? 60 : $time; ?>秒</a>
                </div>
                <div class="login_td">
                    <input type="text" name="captcha" placeholder="请输入验证码" id="captcha"/>
                </div>

            </div>
            <div class="divoi_odi">
                <div class="login_td login-fall">
                    <input type="password" name="newpass" placeholder="请设置密码"/>
                </div>
            </div>
            <div class="divoi_odi">
                <div class="login_td login-fall">
                    <input type="password" name="renewpass" placeholder="请重复密码"/>
                </div>
            </div>
            <div class="login_d"></div>
            <div>
                <?= \wanhunet\helpers\Utils::csrfField() ?>
                <input style="box-shadow: #61a627 0px 5px 0px 0px; background: #6eb92b;" type="submit" value="确认"
                       class="login_btn">
            </div>
        </form>
    </section>
<?php $this->beginBlock('inline_scripts'); ?>
    <script>
        $(document).ready(function () {
            var c = <?= empty($time) ? 60 : $time; ?>;
            var t;
            var timedCount = function () {
                $("#getCaptcha").text(c + '秒');
                c = c - 1;
                if (c === 0 || c <= 0) {
                    clearTimeout(t);
                    $("#getCaptcha").text('超时');
                } else {
                    t = setTimeout(timedCount, 1000);
                }
            };
            timedCount();
        });

    </script>
<?php $this->endBlock(); ?>