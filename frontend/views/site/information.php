<?php
/** @var \wanhunet\base\View $this */
/**
 * @var array $inverstOfeMoney
 * @var array $inverstOfMoney
 * @var array $jiuxinInfo
 * @var array $actives
 */
?>
<div class="box">
    <section>
        <div id="inf-ts">
            <p class="inf-title">微信新标消息</p>
            <aside class="shezhi_div_td">
                <div class="shezhi_div_tdv">
                    <div class="shezhi_div_tddv">
                        <div class="shezhi_div_tddvt">
                            <p>体验标上线倒计时</p>
                        </div>
                        <div class="shezhi_div_tddvf">
                            <p>
                                <?php
                                //                                var_dump($inverstOfMoney);
                                ?>
                                <span>
                                    <?=
                                    $inverstOfeMoney;
                                    ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="shezhi_div_tddv">
                        <div class="shezhi_div_tddvt">
                            <p>普通标的上线倒计时</p>
                        </div>
                        <div class="shezhi_div_tddvf">
                            <p>
                                <span>
                                    <?=
                                    $inverstOfMoney;
                                    ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
        <?php if (count($jiuxinInfo) > 0): ?>
            <div id="inf-ts1">
                <p class="inf-title">玖信贷账户信息</p>
                <aside class="shezhi_div_td">
                    <div class="shezhi_div_tdv">
                        <div class="shezhi_div_tddv">
                            <div class="shezhi_div_tddvt">
                                <p>玖信贷账户名称：</p>
                            </div>
                            <div class="shezhi_div_tddvf">
                                <p><span><?= $jiuxinInfo['j_user_name'] ?></span></p>
                            </div>
                        </div>
                        <div class="shezhi_div_tddv">
                            <div class="shezhi_div_tddvt">
                                <p>总额（元）：</p>
                            </div>
                            <div class="shezhi_div_tddvf">
                                <p><?= $jiuxinInfo['total'] ?></p>
                            </div>
                        </div>
                        <div class="shezhi_div_tddv">
                            <div class="shezhi_div_tddvt">
                                <p>可用额度（元）：</p>
                            </div>
                            <div class="shezhi_div_tddvf">
                                <p><?= $jiuxinInfo['use_money'] ?></p>
                            </div>
                        </div>
                        <div class="shezhi_div_tddv">
                            <div class="shezhi_div_tddvt">
                                <p>冻结（元）：</p>
                            </div>
                            <div class="shezhi_div_tddvf">
                                <p><?= $jiuxinInfo['no_use_money'] ?></p>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        <?php endif; ?>
        <div id="inf-ts4">
            <p class="inf-title">玖信贷活动专区</p>
            <?php foreach ($actives as $active): ?>
                <aside class="shezhi_div_td">
                    <div class="shezhi_div_tdv active_title" style="padding: 10px 8px;">
                        <p><?= $active["title"] ?></p>
                    </div>
                    <div style="padding: 10px;display: none;">
                        <?= $active["content"] ?>
                    </div>
                </aside>
            <?php endforeach; ?>
        </div>
        <script>
            $(document).ready(function () {
                $(".active_title").click(function () {
                    $(this).next("div").toggle();
                });
            });
        </script>
    </section>
    <footer class="ind-footer">
        <aside class="fot">
            <ul>
                <li><a href="<?= \yii\helpers\Url::to(['text/about']) ?>">关于玖信 </a></li>
                <li><a href="javascript:">|</a></li>
                <li><a href="<?= \yii\helpers\Url::to(['text/baozhang']) ?>"> 玖信保障</a></li>
                <li><a href="javascript:">|</a></li>
                <li><a href="<?= \yii\helpers\Url::to(['text/problem']) ?>">常见问题</a></li>
                <li><a href="javascript:">|</a></li>
                <li><a href="<?= \yii\helpers\Url::to(['site/main']) ?>">品牌首页</a></li>
            </ul>
        </aside>
    </footer>
</div>