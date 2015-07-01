<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */
?>
<style>
    li {
        margin-top: 5px;
    }

    ul {
        margin-top: 10px;
    }
</style>
<ul>
    <li><?= \yii\helpers\Html::a('进入玖信贷', \yii\helpers\Url::to(['site/enter']), ['target' => '_blank']) ?></li>
    <li><?= \yii\helpers\Html::a('登陆玖信贷', \yii\helpers\Url::to(['site/signin']), ['target' => '_blank']) ?></li>
</ul>
<ul>
    <li>
        <?= \yii\helpers\Html::a('玖信特权', 'javascript:;') ?>
        <ul>
            <li><?= \yii\helpers\Html::a('主人发特权', \yii\helpers\Url::to(['text/share']), ['target' => '_blank']) ?></li>
            <li><?= \yii\helpers\Html::a('客人拿特权', \yii\helpers\Url::to(['site/signup']), ['target' => '_blank']) ?></li>
        </ul>
    </li>
</ul>
<ul>
    <li>
        <?= \yii\helpers\Html::a('玖信服务', 'javascript:;') ?>
        <ul>
            <li><?= \yii\helpers\Html::a('免登录功能', \yii\helpers\Url::to(['wechat/login']), ['target' => '_blank']) ?></li>
            <li><?= \yii\helpers\Html::a('取消免登录', \yii\helpers\Url::to(['wechat/off']), ['target' => '_blank']) ?></li>
            <li><?= \yii\helpers\Html::a('投资未成功', \yii\helpers\Url::to(['site/enter']), ['target' => '_blank']) ?></li>
            <li><?= \yii\helpers\Html::a('取现未到账', \yii\helpers\Url::to(['site/enter']), ['target' => '_blank']) ?></li>
            <li><?= \yii\helpers\Html::a('VIP专属', \yii\helpers\Url::to(['site/enter']), ['target' => '_blank']) ?></li>
        </ul>
    </li>
</ul>
