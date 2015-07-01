<?php
$member = \wanhunet\wanhunet::app()->member;
$root = \wanhunet\wanhunet::getAlias('@web') . '/';
$idcard = $member->getIdcard();
$idcard1 = $idcard;
$idcard = !empty($idcard) ?
    '<span>' . $idcard . '</span>' :
    '认证获' . \common\models\Config::getInstance()->getProperty('setIncEM.firstIdcard') . '体验金';
$email = $member->email;
$email1 = $email;
$email = !empty($email) ?
    '<span>' . $email . '</span>' :
    '认证获' . \common\models\Config::getInstance()->getProperty('setIncEM.verify_success_email') . '体验金';
?>
<div class="box">
    <section>
        <aside class="shezhi_od">
            <a href="<?= \yii\helpers\Url::to(['site/main']) ?>">
                <img src="<?= $root ?>images/face.png" alt="">
            </a>
        </aside>
        <aside class="shezhi_td">
            <p><?= $member->getPhone() ?></p>
        </aside>

        <a href="<?= \yii\helpers\Url::to(['invest/profit']) ?>">
            <aside class="shezhi_aside">
                <div class="shezhi_div">
                    <div class="shezhi_td_od">
                        <img src="<?= $root ?>images/ziyou.png" alt="">
                    </div>
                    <div class="shezhi_td_d">
                        <p>自有投标</p>
                    </div>
                    <div class="shezhi_div_tddvf">
                        <img src="/images/yu.png" alt="" style="margin-left: 19px;">
                    </div>
                </div>
            </aside>
        </a>
        <a href="<?= \yii\helpers\Url::to(['invest/own-profit', "t" => 1]) ?>">
            <aside class="shezhi_aside">
                <div class="shezhi_div">
                    <div class="shezhi_td_od">
                        <img src="<?= $root ?>images/tiyan.png" alt="">
                    </div>
                    <div class="shezhi_td_d">
                        <p>体验投标</p>
                    </div>
                    <div class="shezhi_div_tddvf">
                        <img src="/images/yu.png" alt="" style="margin-left:19px;">
                    </div>
                </div>
            </aside>
        </a>

        <a href="<?= empty($idcard1) ? \yii\helpers\Url::to(['site/idcard']) : 'javascript:' ?>">
            <aside class="shezhi_aside">
                <div class="shezhi_div">
                    <div class="shezhi_td_od">
                        <img src="<?= $root ?>images/to.png" alt="">
                    </div>
                    <div class="shezhi_td_d">
                        <p>身份认证</p>
                    </div>
                    <div class="shezhi_td_th">
                        <p>
                            <?= $idcard ?>
                        </p>
                    </div>
                </div>
            </aside>
        </a>
        <a href="<?= empty($email1) ? \yii\helpers\Url::to(['site/email']) : 'javascript:' ?>">
            <aside class="shezhi_aside">
                <div class="shezhi_div">
                    <div class="shezhi_td_od">
                        <img src="<?= $root ?>images/youxiang.png" alt="">
                    </div>
                    <div class="shezhi_td_d">
                        <p>邮箱认证</p>
                    </div>
                    <div class="shezhi_td_th">
                        <p>
                            <?= $email ?>
                        </p>
                    </div>
                </div>
            </aside>
        </a>
        <a href="<?= (($memberOther = $member->getOtherInfo(\modules\member\models\MemberOther::TABLE_JIUXIN)) === null) ?
            \yii\helpers\Url::to(['site/setjiuxin']) : 'javascript:' ?>">
            <aside class="shezhi_aside">
                <div class="shezhi_div">
                    <div class="shezhi_td_od">
                        <img src="<?= $root ?>images/to2.png" alt="">
                    </div>
                    <div class="shezhi_td_d">
                        <p>绑定玖信账号</p>
                    </div>
                    <div class="shezhi_td_th">
                        <p>
                            <?php
                            if (($memberOther = $member->getOtherInfo(\modules\member\models\MemberOther::TABLE_JIUXIN)) !== null) {
                                echo '已认证';
                            } else {
                                echo '认证获' . \common\models\Config::getInstance()->getProperty('setIncEM.jiuxin') . '体验金';
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </aside>
        </a>
        <a href="<?= \yii\helpers\Url::to(['site/recharge']) ?>">
            <aside class="shezhi_aside">
                <div class="shezhi_div">
                    <div class="shezhi_td_od">
                        <img src="<?= $root ?>images/to3.png" alt="">
                    </div>
                    <div class="shezhi_td_d">
                        <p>
                            <?php if (count($member->getBankCard()) > 0): ?>
                                充值
                            <?php else: ?>
                                支付账户开通
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="shezhi_td_th">
                        <p>
                            <?php if (count($member->getBankCard()) > 0): ?>
                                <?php
                                $bankCardId = key($member->getBankCard());
                                echo substr($bankCardId, 0, 4) . str_repeat('*', 12) . substr($bankCardId, -3, 3);;
                                ?>
                            <?php else: ?>
                                首次充值获100体验金
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </aside>
        </a>
        <aside class="shezhi_div_td">
            <div class="shezhi_div_tdv">
                <a href="#" class="shezhi_div_tddv">
                    <div class="shezhi_div_tddvo">
                        <img src="<?= $root ?>images/to4.png" alt="">
                    </div>
                    <div class="shezhi_div_tddvt">
                        <p>我的邀请码</p>
                    </div>
                    <div class="shezhi_div_tddvf" style="width:155px">
                        <p><span><?= substr($member->phone, -8, 8) ?></span>（手机号后八位）</p>
                    </div>
                </a>
                <a href="<?= \yii\helpers\Url::to(['site/privilege']) ?>" class="shezhi_div_tddv">
                    <div class="shezhi_div_tddvo">
                        <img src="<?= $root ?>images/to5.png" alt="">
                    </div>
                    <div class="shezhi_div_tddvt">
                        <p>玖信特权</p>
                    </div>
                    <div class="shezhi_div_tddvf">
                        <img src="<?= $root ?>images/yu.png" alt="">
                    </div>
                </a>
                <a href="<?= \yii\helpers\Url::to(['site/information']) ?>" class="shezhi_div_tddv">
                    <div class="shezhi_div_tddvo">
                        <img src="<?= $root ?>images/to6.png" alt="">
                    </div>
                    <div class="shezhi_div_tddvt">
                        <p>玖信消息</p>
                    </div>
                    <div class="shezhi_div_tddvx">
                    </div>
                    <div class="shezhi_div_tddvf">
                        <img src="<?= $root ?>images/yu.png" alt="">
                    </div>
                </a>
            </div>
        </aside>
        <aside class="shezhi_div_th">
            <div class="shezhi_div_thd">
                <a href="<?= \yii\helpers\Url::to(['site/change-password']) ?>" class="shezhi_divth">
                    <div class="shezhi_td_od">
                        <img src="<?= $root ?>images/to7.png" alt="">
                    </div>
                    <div class="shezhi_td_d">
                        <p>修改登录密码</p>
                    </div>
                    <div class="shezhi_td_thd">
                        <img src="<?= $root ?>images/yu.png" alt="">
                    </div>
                </a>
                <a href="<?= \yii\helpers\Url::to(['site/change-pay-password']) ?>" class="shezhi_divth">

                    <div class="shezhi_td_od">
                        <img src="<?= $root ?>images/to8.png" alt="">
                    </div>
                    <div class="shezhi_td_d">
                        <p>修改取现密码</p>
                    </div>
                    <div class="shezhi_td_thd">
                        <img src="<?= $root ?>images/yu.png" alt="">
                    </div>
                </a>
                <a href="<?= \yii\helpers\Url::to(['site/reset-pay-pass']) ?>" class="shezhi_divth">
                    <div class="shezhi_td_od">
                        <img src="<?= $root ?>images/to9.png" alt="">
                    </div>
                    <div class="shezhi_td_d">
                        <p>忘记取现密码</p>
                    </div>
                    <div class="shezhi_td_thd">
                        <img src="<?= $root ?>images/yu.png" alt="">
                    </div>
                </a>
            </div>
        </aside>
        <div class="shezhi_dif">
            <a href="<?= \yii\helpers\Url::to(['site/logout']) ?>" class="shezhi_dfi" style="">
                <p>退出登录</p>
            </a>
        </div>
    </section>
</div>