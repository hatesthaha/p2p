<?php
$root = \wanhunet\wanhunet::getAlias('@web') . '/';
/** @var modules\cms\models\Post[] $posts */
?>
    <div>
        <section class="paged">
            <div class="mb10">
                <img class="mcenter" width="200px;" src="<?= $root ?>images/problem.png" alt="常见问题">
            </div>
            <div class="page">
                <dl class="page_od">

                    <?php foreach ($posts as $key => $post): ?>
                        <dt class="slider_open" style="border-top:0">
                            <div class="page_od_od" id="p_1">
                                <p><img src="<?= $root ?>images/problem-icon.png" alt="问题"><?= $post['title'] ?></p>
                            </div>
                        </dt>
                        <dd style="display: none;">
                            <p><?= $post['content'] ?></p>
                        </dd>
                    <?php endforeach; ?>

                </dl>
            </div>
        </section>
    </div>
<?php $this->beginBlock('inline_scripts'); ?>
    <script src="<?= $root ?>js/problem.js"></script>
    <script>
        $(document).ready(function () {
            var UA = window.navigator.userAgent;  //使用设备
            var CLICK = "click";
            if (/ipad|iphone|android/.test(UA)) {   //判断使用设备
                CLICK = "tap";
            }
            $('.slider_open')[CLICK](function () {
                $(this).next().toggle();
            })
        });
    </script>
<?php $this->endBlock(); ?>