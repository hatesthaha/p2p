<?php $root = \wanhunet\wanhunet::getAlias('@web') . '/'; ?>

    <div class="p15 hui8">
        <section>
            <aside>
                <table>
                    <tr>
                        <td width="50%">
                            <img class="floatLeft" width="90%" style="max-width:173px;"
                                 src="<?= $root ?>images/check-box.png" alt="">
                        </td>
                        <td width="50%">
                            <p class="rule-title">使用规则</p>

                            <p>1.新用户注册拿特权</p>

                            <p>新用户注册即获至少2000元体验金。</p>

                            <p>2.老用户分享拿特权</p>

                            <p>邀请好友注册成为玖信理财新用户，成功邀请一位即可获得500元体验金，邀请无上限。</p>

                            <p>3.体验金期限为1个月，没有在当月使用的体验金过期清零。</p>
                        </td>
                    </tr>
                </table>
            </aside>
            <aside>
                <p class="rule-title">使用规则</p>

                <p>1.体验金可投体验标，到期之后可以取出利息，玖信理财将收回体验金。</p>

                <p>2.每个体验标的投标上限为2000元，但体验投标上限可以随投资真实标的的金额上升。用户本人和用户好友的真实投资总额达到1000元，投标上限即增加1000元。</p>
            </aside>
            <aside style="margin-top: 20px;">
                <p class="rule-title">好友扫描也可以哦</p>

                <p>
                    <iframe src="<?= \yii\helpers\Url::to(['text/share', 'qrcode' => urlencode($url)]) ?>"
                            style="display: block;border: hidden;width: 260px;height: 260px;margin: 0 auto;"></iframe>
                </p>
            </aside>
            <div class="login_fo">
                <p>*以上内容最终解释权归玖信理财所有。</p>
            </div>
            <div class="login_fo">
                <a style="width:100%;" class="have-btn" href="javascript:" id="share">马上分享拿体验金</a>
            </div>

            <div id="mask"
                 style="position: absolute; top: 0px;left: 0px; width: 100%; text-align: center; height: 0; z-index: 99999; opacity: 0.8; background-color: rgb(0, 0, 0);"></div>
            <div style="position: fixed;top: 0;right: 0;width: 100%;height: 100%;z-index: 100001; display:none;"
                 id="share1">
                <img src="<?= \wanhunet\wanhunet::getAlias("@web") ?>/images/shareBgz.png"
                     style="width:70%;margin-left:26.5%;">
            </div>

            <script>
                $(document).ready(function () {
                    $("#share").click(function () {
                        $("#mask").css("height", $(document).height() + "px");
                        $("#share1").css("display", "block");
                    });
                });
            </script>
        </section>
        <footer class="ind-footer">
            <aside class="fot">
                <ul>
                    <li><a href="<?= \yii\helpers\Url::to(['text/about']) ?>">关于玖信 </a></li>
                    <li><a href="javascript:">|</a></li>
                    <li><a href="<?= \yii\helpers\Url::to(['text/baozhang']) ?>">玖信保障</a></li>
                    <li><a href="javascript:">|</a></li>
                    <li><a href="<?= \yii\helpers\Url::to(['text/problem']) ?>">常见问题</a></li>
                    <li><a href="javascript:">|</a></li>
                    <li><a href="<?= \yii\helpers\Url::to(['site/main']) ?>">品牌首页</a></li>
                </ul>
            </aside>
        </footer>
    </div>
<?php $this->beginBlock('inline_scripts'); ?>

    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        $(document).ready(function () {
            var errorAuth = '<?=$errorAuth?>';
            if (errorAuth !== '') {
//                alertText('<?//=$errorAuth?>//');
            }
        });
        wx.config(<?= json_encode(\wanhunet\wanhunet::app()->wechat->jsApiConfig([],false)) ?>);
        wx.ready(function () {
            wx.onMenuShareTimeline({
                title: '庆玖信理财上线  任性老板，无限发体验金...', // 分享标题
                desc: '玖信出本金，您来赚收益！收益免费提现！', // 分享描述
                imgUrl: '<?= \wanhunet\wanhunet::$app->request->hostInfo . \wanhunet\wanhunet::getAlias("@web") ?>/images/hongbao.png', // 分享图标
                link: '<?=$url?>', // 分享链接
                success: function () {
                    WeixinJSBridge.call('closeWindow');
                }
            });
            wx.onMenuShareAppMessage({
                title: '庆玖信理财上线  任性老板，无限发体验金...', // 分享标题
                desc: '玖信出本金，您来赚收益！收益免费提现！', // 分享描述
                imgUrl: '<?= \wanhunet\wanhunet::$app->request->hostInfo . \wanhunet\wanhunet::getAlias("@web") ?>/images/hongbao.png', // 分享图标
                link: '<?=$url?>', // 分享链接
                type: 'link', // 分享类型,music、video或link，不填默认为link
                success: function () {
                    WeixinJSBridge.call('closeWindow');
                }
            });
        });
    </script>

<?php $this->endBlock(); ?>