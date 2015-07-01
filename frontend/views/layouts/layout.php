<?php
use yii\helpers\Html;

/* @var $this \wanhunet\base\View */
/* @var $content string */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="<?= \wanhunet\wanhunet::getAlias('@web') . '/' ?>css/reset.css">
    <link rel="stylesheet" href="<?= \wanhunet\wanhunet::getAlias('@web') . '/' ?>css/style.css">
    <script src="<?= \wanhunet\wanhunet::getAlias('@web') . '/' ?>js/jquery.min.js"></script>
    <?= Html::csrfMetaTags() ?>
    <title><?= !empty($this->title) ? Html::encode($this->title) . "_" : "" ?>玖信理财</title>
    <?php $this->head() ?>
</head>
<body <?php if (isset($this->blocks['body_attr'])): ?><?= $this->blocks['body_attr'] ?><?php endif; ?>>
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
<section class="zhezhao" id="alertTextBg" style="display: none;">
</section>
<aside class="showDiv" id="alertTextDL" style="display: none;">
    <div class="zhe" id="zhe2" style="display: block;">
        <p class="ablum-edit">
            <img id="alertTextclose" width="15px;"
                 src="<?= \wanhunet\wanhunet::getAlias('@web') . '/' ?>images/smalldel.png"
                 alt="">
        </p>

        <div class="clearFloat zhe-box">
            <img class="floatLeft" height="80px"
                 src="<?= \wanhunet\wanhunet::getAlias('@web') . '/' ?>images/check-setupbox.png" alt="">

            <h1 style="padding-top: 13px;" id="alertTextsc"></h1>
        </div>
        <span id="alertTexts">确定</span>
    </div>
</aside>

</body>
<script>
    function alertText(info) {
        $("#alertTextBg").show();
        $("#alertTextDL").show();
        $("#alertTextsc").text(info);
    }
    var UA = window.navigator.userAgent; //使用设备
    var CLICK = "click";
    if (/ipad|iphone|android/.test(UA)) { //判断使用设备
        CLICK = "tap";
    }
    $(document).ready(function () {
        var CLICK = "click";
        if (/ipad|iphone|android/.test(window.navigator.userAgent)) { //判断使用设备
            CLICK = "tap";
        }
        $("#alertTextclose,#alertTexts")[CLICK](function () {
            event.preventDefault();
            $("#alertTextBg").css("display", "none");
            $("#alertTextDL").css("display", "none");
        });

        <?php
            if($this->getParamsErrors('info',null) !== null):
        ?>
        alertText('<?= $this->getParamsErrors('info','')?>');
        <?php
            endif;
        ?>
    });

</script>
<?php if (isset($this->blocks['inline_scripts'])): ?>
    <?= $this->blocks['inline_scripts'] ?>
<?php endif; ?>

</html>
<?php $this->endPage() ?>
