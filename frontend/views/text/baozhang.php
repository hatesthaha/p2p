<?php $root = \wanhunet\wanhunet::getAlias('@web') . '/'; ?>
<div>
    <section class="paged">
        <div class="mb10">
            <img class="mcenter" width="200px;" src="<?= $root ?>images/about.png" alt="常见问题">
        </div>
        <?= $post->content ?>
    </section>
</div>
