<?php
/** @var \wanhunet\base\View $this */
if (($order = \wanhunet\wanhunet::$app->getSession()->get('order')) !== null) {

    if (is_array($order)) {
        $price = \modules\asset\models\AssetMoney::findOne($order[1])->getPrice();
        $action = \yii\helpers\Url::to(['pay/mention']);
    } else {
        $price = \modules\invest\models\InvestList::findOne($order)->getPrice();
        $action = \yii\helpers\Url::to(['pay/pay-with-balance']);
    }
}

?>
    <section class="login_bt divoi wh-addpage" style="padding:20px 8px 0;">
        <form class="wh-addform" id="form" action="<?= $action ?>" method="post">
            <div class="divoi_odi">
                <div class="login_td login-fall">
                    <input style="text-align:center;" type="password" name="paypass" placeholder="请输入交易密码"/>
                </div>
            </div>
            <p class="font-s16 wh-addptop mb10">为了保证资金安全，请输入您的交易密码，默认为登录密码</p>

            <p class="font-s16 mt20 mb10">支付金额：<span
                    class="hui8">
                <?= $price ?>
                    元</span></p>

            <div class="btn-box2 clearFloat" style="margin-top:50px;">
                <a class="wh-addbtn wh-huibtn" href="<?= \yii\helpers\Url::to(['invest/list']) ?>">取 消</a>
                <a class="wh-addbtn" href="javascript:" id="submit">确 定</a>
            </div>
        </form>

    </section>
<?php $this->beginBlock('inline_scripts'); ?>
    <script>
        $(document).ready(function () {
            $('#submit').click(function () {
                $("#form").submit();
            });
        });
    </script>
<?php $this->endBlock(); ?>