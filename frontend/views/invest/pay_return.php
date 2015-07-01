<?php /** @var $res  */?>
<section class="login_bt">
    <div class="divoi wh-addpage">
        <p class="pro-success">购买成功！</p>

        <p class="red-paper">
            <img width="80%" src="<?= \wanhunet\wanhunet::getAlias('@web') . '/' ?>images/red-paper.png" alt="红包">
        </p>

        <p>如有疑问请致电客服电话：400-005-8181</p>

        <div style="margin:25px 0;">
            <a class="wh-addbtn" href="<?= \yii\helpers\Url::to(['site/main']) ?>" id="getCaptcha"
               style="display:block">确 定</a>
        </div>
    </div>
</section>