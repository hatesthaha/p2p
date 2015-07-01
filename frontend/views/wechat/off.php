<section class="login_bt" style="padding-top:20px;">
    <form id="form" action="<?= \yii\helpers\Url::to(['wechat/off']) ?>" method="post">
        <div>
            <input type="hidden" name="open_id" value="<?= \wanhunet\wanhunet::$app->request->get('open_id') ?>"/>
            <?= \wanhunet\helpers\Utils::csrfField() ?>
            <input style="box-shadow: #61a627 0px 5px 0px 0px; background: #6eb92b;margin-top: 30%;" type="submit" value="关闭免登录模式"
                   class="login_btn">
        </div>
    </form>
</section>
