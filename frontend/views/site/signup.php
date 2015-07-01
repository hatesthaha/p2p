<?php
use yii\helpers\Url;

$this->title = "注册";
$session = \wanhunet\wanhunet::$app->session;

$phone = '';
$vcodeError = false;
if ($session->hasFlash('errors')) {
    $errors = $session->getFlash('errors');
    $phone = $errors['signup.phone'];
    $vcodeError = $errors['signup.show'] == 2 ? true : false;
}
?>
    <section id="signup1" <?= !$vcodeError ? '' : 'style="display: none;"' ?>>
        <form>
            <div class="login">
                <div class="login_od">
                    <p>+86</p>
                </div>
                <div class="login_td">
                    <input type="number" maxlength="11" placeholder="请输入手机号码" name="phoneNumber" id="phone">
                    <?= \wanhunet\helpers\Utils::csrfField(); ?>
                </div>
            </div>
            <div class="login_div">
                <a href="<?= Url::to(['text/xieyi']) ?>">查看用户协议</a>
            </div>
            <aside class="login_bt">
                <div>
                    <input type="submit" value="下一步" class="login_btn" id="bt1">
                </div>
            </aside>
            <aside class="login_fo">
                <p>*玖信理财承诺不会泄露您的个人信息*</p>
            </aside>
        </form>
    </section>

    <section class="login_bt" id="signup2" <?= $vcodeError ? '' : 'style="display: none;"' ?>>
        <div class="divoi">
            <p>
                我们已发送短信验证码至<span id="phoneNum"></span>，请在输入框内填写验证码，若未收到请耐心等候~
            </p>
        </div>

        <form action="<?= Url::to(['site/signup-verify']) ?>" method="post">
            <div class="divoi_odi">
                <div class="login_od" style="">
                    <a href="javascript:" id="getCaptcha" style="display:block">60秒</a>
                </div>
                <div class="login_td">
                    <input type="hidden" name="phone" id="phone2"
                           value="<?= $phone ?>"/>
                    <input type="text" placeholder="请输入验证码" name="captcha" id="captcha"/>
                </div>

            </div>
            <div class="divoi_odi">
                <div class="login_td login-fall">
                    <input type="password" name="password" placeholder="请设置密码" id="pass"/>
                </div>
            </div>
            <div class="divoi_odi">
                <div class="login_td login-fall">
                    <input type="password" placeholder="请重复密码" id="repass"/>
                </div>
            </div>
            <div class="divoi_odi">
                <div class="login_td login-fall">
                    <input name="invitation" type="number" placeholder="邀请码" id="repass"
                           value="<?= \wanhunet\wanhunet::$app->request->get('parent', '') ?>"/>
                </div>
            </div>
            <?= \wanhunet\helpers\Utils::csrfField() ?>
            <div class="login_d"></div>
            <div>

                <input style="box-shadow: #61a627 0px 5px 0px 0px; background: #6eb92b;" type="submit" value="注册完成"
                       class="login_btn" id="bt2">
            </div>
        </form>
    </section>
    <div style="height: 100px;" id="blank"></div>
    <footer style="bottom:5px;right:0; width:100%;">
        <aside class="fot">
            <ul>
                <li><a href="<?= \yii\helpers\Url::to(['text/about']) ?>">关于玖信 </a></li>
                <li><a href="javascript:">|</a></li>
                <li><a href="<?= \yii\helpers\Url::to(['text/baozhang']) ?>"> 玖信保障</a></li>
                <li><a href="javascript:">|</a></li>
                <li><a href="<?= \yii\helpers\Url::to(['text/problem']) ?>">常见问题</a></li>
                <li><a href="javascript:">|</a></li>
                <li><a href="<?= \yii\helpers\Url::to(['site/main']) ?>">品牌首页</a></li>
            </ul>
        </aside>
    </footer>
<?php $this->beginBlock('inline_scripts'); ?>
    <script>
        $(document).ready(function () {
            $("#blank").height($(document).height() - 251);
            var c = 5;
            var t;
            var timedCount = function () {
                $("#getCaptcha").text(c + '秒');
                c = c - 1;
                if (c === 0 || c <= 0) {
                    clearTimeout(t);
                    $("#getCaptcha").html('获取');
                    $("#getCaptcha").bind('click', function () {
                        location.reload();
                    });
                } else {
                    t = setTimeout(timedCount, 1000);
                }
            };

            $("#bt1").click(function () {
                var phone = $('#phone');
                $.post('<?= Url::to(['site/signup'])?>', {
                    phone: phone.val(),
                    _csrf: $('#csrf').val()
                }, function (JsonData) {
                    var data = JsonData;
                    if (data.status === 0) {
                        alertText(data.info);
                    } else {
                        if (data.status === 2) {
                            $("#getCaptcha").html(data.info + '秒');
                            c = data.info;
                        }
                        timedCount();
                        $("#phone2").val($("#phone").val());
                        $("#phoneNum").text(phone.val().substr(0, 3) + '****' + phone.val().substr(-4, 4));
                        $('#signup1').hide();
                        $('#signup2').show();
                        $("#blank").height($(document).height() - 690);
                    }
                });
                return false;
            });

            $("#bt2").click(function () {
                if ($("#pass").val() === $("#repass").val()) {
                    return true;
                } else {
                    alertText('您输入的密码不一样');
                    return false;
                }
            });


        });

    </script>
<?php $this->endBlock(); ?>