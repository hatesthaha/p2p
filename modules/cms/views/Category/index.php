<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $categoryTree */
/* @var $pages */

$this->title = '内容管理';
?>
<div class="row">
    <div class="col-xs-12">
        <a class="btn" href="<?= \yii\helpers\Url::to(['create']) ?>">
            <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
            新建栏目
        </a>
    </div>
    <div class="col-xs-12" style="margin-top: 20px;">
        <table id="simple-table" class="table table-striped table-bordered table-hover">
            <thead>

            <tr>
                <th class="center">
                    <label class="pos-rel">
                        <!--                            <input type="checkbox" class="ace">-->
                        <span class="lbl">ID</span>
                    </label>
                </th>
                <th>标题</th>
                <th>
                    <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
                    创建时间
                </th>

                <th>
                    <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
                    修改时间
                </th>
                <th class="hidden-480">状态</th>

                <!--<th>
                    操作
                </th>-->
            </tr>
            </thead>

            <tbody>

            <?php
            function build($categoryTree)
            {
                if (!empty($categoryTree)) :
                    foreach ($categoryTree as $v):
                        $parent = $v['parent'];
                        $child = $v['child'];
                        ?>
                        <tr>
                            <td class="center">
                                <label class="pos-rel">
                                    <span class="lbl"><?= $parent['id']; ?></span>
                                </label>
                            </td>

                            <td>
                                <?php echo str_repeat('<span style="display: inline-block;width: 20px;"></span>', ($v['level'] - 1));?>
                                <a href="<?= \yii\helpers\Url::to(['view', 'id' => $parent['id']]) ?>">
                                    <?= Html::encode($parent['title']) ?>
                                </a>
                            </td>
                            <td>
                                <?= date('m-d H:i', $parent['created_at']) ?>
                            </td>
                            <td>
                                <?= date('m-d H:i', $parent['updated_at']) ?>
                            </td>

                            <td class="hidden-480">
                                <?php if ($parent['status'] == \wanhunet\db\ActiveRecord::STATUS_ACTIVE): ?>
                                    <span class="label label-sm label-success">显示</span>
                                <?php else: ?>
                                    <span class="label label-sm label-warning">隐藏</span>
                                <?php endif;?>
                            </td>
                        </tr>
                        <?php
                        build($child);
                    endforeach;
                endif;
            }

            build($categoryTree);
            ?>
            </tbody>
        </table>
    </div>
    <!-- /.span -->
</div>