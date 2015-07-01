<?php /** @var $wechat_info */ ?>
<?php /** @var $emCount */ ?>
<?php $this->beginBlock('body_attr'); ?>class="bag-bg"<?php $this->endBlock(); ?>
    <style>
        @media screen and (min-width: 480px)
        body {
            font-size:

        24px

        ;
        }

        @media screen and (min-width: 320px)
        body {
            font-size:

        16px

        ;
        }

        body {
            height: auto;
        }

        .LookfrienBtn {
            width: 21.44em;
        }
    </style>
    <div id="loadDiv" class="main" style="display: block;">
        <div class="redBox imeeShareBg  clearfix" style="margin-bottom: 1.5em;height:auto;">
            <div class="showOutHead" data-zoom="margin-bottom: 2.5em;">
                <img src="<?= isset($wechat_info['headimgurl']) ? $wechat_info['headimgurl'] : "" ?>"
                     data-zoom="zoom:1.4;">
            </div>

            <div class="showOut1" data-zoom="zoom:1.1;">您已抢过理财 体验金 了~~:)</div>
            <ul class="showOutBox" style="width: 100%;margin: 0 auto;color: #fff;text-align: center;">
                <li class="showOut2" style="font-size:1.2em;" data-zoom="zoom:1.1;font-size:1.2em;">
                    仍有
                    <o style="font-size:1.5em"><?= $emCount ?></o>
                    个体验金未领取
                </li>
            </ul>
            <br>
            <a class="redBagBtn" href="javascript:void(0);" style="width: 70%;margin-bottom:1em;">立即分享</a>
            <br>
            <a class="redBagBtn" href="<?= \yii\helpers\Url::to(['site/enter', 'open_id' => $wechat_info['openid']]) ?>"
               style="width: 70%;line-height: 2.05em;background: #961313;border: 2px solid #fff;border-radius: 0.1666em;">进入玖信理财</a>
        </div>
        <div class="LookfrienBtn" data-zoom="zoom:1.2;" style="width: 98%;height: 30px;">
            <a style="width: 33.1%;height: 30px;line-height: 30px;" href="javascript:void(0);" class="friendCurrent" data-cato="friend">看朋友手气</a>
            <a style="width: 33.1%;height: 30px;line-height: 30px;" href="javascript:void(0);" class="" data-cato="activity">活动详情</a>
            <a style="width: 33.1%;height: 30px;line-height: 30px;" href="javascript:void(0);" class="" data-cato="know">了解玖信理财</a>
        </div>
        <div class="shareBox" data-zoom="zoom:1.12;" style="zoom: 0.95; display: block;" id="friend">

            <?php foreach ($members as $member): ?>
                <?php
                $wechatMember = \modules\asset\models\ShareGift::find()->where(['user_id' => $member['id']])->asArray()->one();
                ?>
                <?php if (count($wechatMember) > 0): ?>
                    <?php $info = json_decode($wechatMember['wechat_info'], true) ?>
                    <ul class="friendList clearFloat">
                        <li class="floatLeft friendImg">
                            <img src="<?= $info['headimgurl'] ?>">
                        </li>
                        <li class="floatLeft friendName">
                            <dl>
                                <dt>
                                    <o><span><?= date("m-d H:i", $member['created_at']) ?></span></o>
                                    <b><?= $info['nickname'] ?></b>
                                </dt>
                                <dd>不出本金还能赚收益，么么哒！</dd>
                            </dl>
                        </li>
                        <li class="floatRight friendMonney">
                            <o><?= $wechatMember['gift_limit'] ?></o>
                            元
                        </li>
                    </ul>
                <?php endif; ?>
            <?php endforeach; ?>


        </div>
        <ul id="activity" class="shareBox friendXiang hide frienXiMar clearfix" style="display: none;">
            <li style="list-style-type: disc;color: #fff;margin-top:0.6em;">玖信理财出本金，用户投资赚收益!</li>
            <li style="list-style-type: disc;color: #fff;margin-top:0.6em;">参与活动领取
                <o>10</o>
                万元红包，分享到朋友群，红包收益更给力！（注：由于活动过分火爆，分享到朋友圈好友看不见哦。你懂的！)
            </li>
            <li style="list-style-type: disc;color: #fff;margin-top:0.6em;">
                抢到的理财本金红包用于投资红包专用标，到期后用户获得收益，平台收回本金。收益可投资可提现。
            </li>
            <li style="list-style-type: disc;color: #fff;margin-top:0.6em;">朋友抢到投资理财本金红包，在活动期间注册并绑银行卡，再奖励分享人
                <o>5000</o>
                元理财本金红包。注册并绑银行卡越多，奖励越多！<p></p></li>
        </ul>
        <ul id="know" class="shareBox friendXiang hide frienXiMar clearfix" style="display: none;">
            <li style="list-style-type: disc;color: #fff;margin-top:0.6em;">玖信家多宝是一款互联网理财产品，
                <o>1000</o>
                起投，年化最高
                <o>10%</o>
                ，投资期限以
                <o>1-3</o>
                个月为主，保本保息。
            </li>
            <li style="list-style-type: disc;color: #fff;margin-top:0.6em;">家多宝资金的投向，主要用于借款人房产交易过程中所产生的短期资金周转。</li>
            <li style="list-style-type: disc;color: #fff;margin-top:0.6em;">采取线下征信审核，依托玖信平台优势，确保借款人的还款途径安全可靠。</li>
            <li style="list-style-type: disc;color: #fff;margin-top:0.6em;">中融信担保公司为所有借款项目提供
                <o>100%</o>
                本息担保，借款人一旦发生逾期还款，即启动
                <o>1000</o>
                万保证金进行本息代偿。
            </li>
            <li style="list-style-type: disc;color: #fff;margin-top:0.6em;">用户资金在第三方进行托管，受中国人民银行监管，玖信不触碰用户资金。</li>
        </ul>
    </div>
    <div id="mask"
         style="position: absolute; top: 0px; width: 100%; text-align: center; height: 0; z-index: 99999; opacity: 0.8; background-color: rgb(0, 0, 0);"></div>
    <div style="position: fixed;top: 0;right: 0;width: 100%;height: 100%;z-index: 100001; display:none;" id="share">
        <img src="<?= \wanhunet\wanhunet::getAlias("@web") ?>/images/shareBgz.png" style="width:70%;margin-left:26.5%;">
    </div>
    <script>
        $(document).ready(function () {
            $("body").css("min-height", $(window).height());
            var UA = window.navigator.userAgent;  //使用设备
            var CLICK = "click";
            if (/ipad|iphone|android/.test(UA)) {   //判断使用设备
                CLICK = "tap";
            }
            var catoFram = $(".shareBox");
            var subNav = $(".LookfrienBtn a");
            catoFram[0].style.display = "block";
            subNav[0].className += " friendCurrent";
            subNav[CLICK](function () {
                var _this = $(this);
                var id = _this.data("cato");
                var cur = $("#" + id);
                subNav.removeClass("friendCurrent");
                _this.addClass("friendCurrent");
                catoFram.hide();
                cur.scrollTop(0);
                cur.show();
            });
            $(".redBagBtn").click(function () {
                var _hh = $(document).height();
                $("#mask").css("height", _hh + "px");
                $("#share").css("display", "block");

            });
        });
    </script>
<?php $this->beginBlock('inline_scripts'); ?>

    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        wx.config(<?= json_encode(\wanhunet\wanhunet::app()->wechat->jsApiConfig([],false)) ?>);
        wx.ready(function () {
            wx.onMenuShareTimeline({
                title: '庆玖信理财上线  任性老板，无限发体验金...', // 分享标题
                desc: '玖信出本金，您来赚收益！收益免费提现！', // 分享描述
                imgUrl: '<?= \wanhunet\wanhunet::$app->request->hostInfo . \wanhunet\wanhunet::getAlias("@web") ?>/images/hongbao.png', // 分享图标
                link: '<?=$url?>', // 分享链接
                success: function () {
                    window.location.href = '<?= \yii\helpers\Url::to(['site/enter', 'open_id' => $wechat_info['openid']]) ?>';
                }
            });
            wx.onMenuShareAppMessage({
                title: '庆玖信理财上线  任性老板，无限发体验金...', // 分享标题
                desc: '玖信出本金，您来赚收益！收益免费提现！', // 分享描述
                imgUrl: '<?= \wanhunet\wanhunet::$app->request->hostInfo . \wanhunet\wanhunet::getAlias("@web") ?>/images/hongbao.png', // 分享图标
                link: '<?=$url?>', // 分享链接
                type: 'link', // 分享类型,music、video或link，不填默认为link
                success: function () {
                    alertText('分享成功');
                    window.location.href = '<?= \yii\helpers\Url::to(['site/enter', 'open_id' => $wechat_info['openid']]) ?>';
                }
            });
        });
    </script>

<?php $this->endBlock(); ?>