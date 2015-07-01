<?php
$web = \wanhunet\wanhunet::getAlias("@web") . "/";
/** @var  modules\invest\models\InvestList $info */
?>
<section>
    <div class="divoi">
        <p class="text-c bag-accept">
            <img class="mcenter" width="30px" height="30px" src="<?= $web ?>images/accept.png"
                 alt="成功">
            <br>恭喜，领取成功！
        </p>
    </div>
    <div class="login_bt">
        <p class="accept-con">
            <img class="floatLeft" width="8px" height="8px" src="<?= $web ?>images/circle.png"
                 alt="">系统已为您自动购买<span><?= $info->investment_sum ?></span>元红包专用标</p>

        <p class="accept-con">
            <img class="floatLeft" width="8px" height="8px" src="<?= $web ?>images/circle.png" alt="">收益
            <span><?= \wanhunet\helpers\Utils::moneyFormat($info->interest) ?></span>元
            已放入玖信理财
        </p>

        <p class="accept-con"><?= $info->member->username ?>账户中;</p>
        <a href="<?= \yii\helpers\Url::to(['site/main']) ?>" class="login_btn mt10 orglogin-btn" id="bt">进入玖信理财</a>
    </div>
</section>