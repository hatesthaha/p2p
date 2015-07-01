<?php
/** @var modules\asset\models\ShareGift $shareGift */
/** @var $num */
?>
<?php $this->beginBlock('body_attr'); ?>class="bag-bg"<?php $this->endBlock(); ?>
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
        <div id="loadDiv" class="main" style="display: block;">
            <div class="imeeShareBg clearFloat">
                <div class="imeeShare" style="position: relative;">
                    <div class="RobOthersDiv" data-zoom="left: 34%;font-size:2.8em;" style="left: 34%;font-size:2.8em;">
                        ¥<?= $shareGift['gift_limit'] ?>
                    </div>
                    <img src="<?= \wanhunet\wanhunet::getAlias("@web") . '/' ?>images/RobOthers.png">
                </div>
                <p class="homelinkeapriDold" style="margin-top:1.9em;" data-zoom="margin-top:1.9em;">
                    <label class="RobMar" data-zoom="zoom:1.2;" style="zoom:1.2;">恭喜您</label>
                    <b style="zoom:1.2;line-height: 1.5em;" data-zoom="zoom:1.2;line-height: 1.5em;"> 抢到
                        <span style="font-family: arial;color:#fac852;"><?= $shareGift['gift_limit'] ?></span>元玖信理财本金红包
                    </b>
                    <b style="zoom:1.2;line-height: 1.5em;" data-zoom="zoom:1.2;line-height: 1.5em;">投资收益
                        <span style="font-family: arial;color:#fac852;"><?= $num ?></span>元全归你！
                    </b>
                </p>

                <form id="fm" method="get">
                    <div class="redBagTelh">
                        <input type="number" maxlength="11" placeholder="请输入手机号码" name="phoneNumber" id="phone"
                               style="width: 66%;">
                        <?= \wanhunet\helpers\Utils::csrfField(); ?>
                    </div>
                    <a class="redBagBtn" href="javascript:;" style="width: 66%;" id="bt1">马上领取</a>
                </form>
            </div>
        </div>
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
                <input type="hidden" name="action_do" value="share"/>
                <input type="hidden" name="wechat_id" value="<?= $wechat_id ?>"/>
                <input style="box-shadow: #61a627 0px 5px 0px 0px; background: #6eb92b;" type="submit" value="注册完成"
                       class="login_btn" id="bt2">
            </div>
        </form>
    </section>
<?php $this->beginBlock('inline_scripts'); ?>
    <script>
        $(document).ready(function () {
            $("#blank").height($(document).height() - 251);
            var c = 60;
            var t;
            var timedCount = function () {
                $("#getCaptcha").text(c + '秒');
                c = c - 1;
                if (c === 0 || c <= 0) {
                    clearTimeout(t);
                    $("#getCaptcha").text('获取');
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
                        $("body").css("background", "#FFF");
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