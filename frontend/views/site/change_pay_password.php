<?php
$memeber = \wanhunet\wanhunet::app()->member;
?>
<section class="login_bt" style="padding-top:20px;">

    <form id="form" action="<?= \yii\helpers\Url::to(['site/change-pay-password']) ?>" method="post">
        <div class="divoi_odi">
            <div class="login_td login-fall">
                <input type="text" placeholder="账户名称：<?= $memeber->getPhone() ?>" readonly="readonly">
            </div>
        </div>
        <div class="divoi_odi">
            <div class="login_td login-fall">
                <input type="password" placeholder="请输入原取现密码" name="paypass"/>
            </div>
        </div>
        <div class="divoi_odi">
            <div class="login_td login-fall">
                <input type="password" placeholder="请输入新取现密码" name="newpass"/>
            </div>
        </div>
        <div class="divoi_odi">
            <div class="login_td login-fall">
                <input type="password" placeholder="请重复新取现密码" name="renewpass"/>
            </div>
        </div>
        <div>
            <?= \wanhunet\helpers\Utils::csrfField() ?>
            <input style="box-shadow: #61a627 0px 5px 0px 0px; background: #6eb92b;" type="submit" value="确认修改"
                   class="login_btn mt10" id="bt">
        </div>
    </form>
</section>
