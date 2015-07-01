<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model */
/* @var $pages */

$this->title = '内容管理';
?>
<div class="row">
    <div class="col-xs-12">
        <a class="btn" href="<?= \yii\helpers\Url::to(['create']) ?>">
            <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
            新建文章
        </a>
    </div>
    <div class="col-xs-12" style="margin-top: 20px;">
        <form action="<?= \yii\helpers\Url::to(['/cms/post/index']) ?>" method="get" id="form">
            <input type="hidden" value="<?= \wanhunet\wanhunet::$app->controller->getRoute() ?>" name="r"/>
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
                        <select id="category" name="category">
                            <option value="">All</option>
                            <?php foreach (\modules\cms\models\Category::eachCategoryTree()['data'] as $key => $item): ?>
                                <option value="<?= $key ?>"><?= $item ?></option>
                            <?php endforeach; ?>
                        </select>
                        <script>
                            $(document).ready(function () {
                                var categoryId = '<?=\wanhunet\wanhunet::$app->request->get("category")?>';
                                $("#category").get(0).value = categoryId;
                                $("#category").change(function () {
                                    $("#form").submit();
                                });
                            });
                        </script>
                    </th>
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
                foreach ($model as $vo):
                    /* @var $vo \modules\cms\models\Post */
                    ?>
                    <tr>
                        <td class="center">
                            <label class="pos-rel">
                                <span class="lbl"><?= $vo->id; ?></span>
                            </label>
                        </td>

                        <td>
                            <a href="<?= \yii\helpers\Url::to(['view', 'id' => $vo->id]) ?>">
                                <?= Html::encode($vo->title) ?>
                            </a>
                        </td>
                        <td>
                            <?php echo($vo->category['title']) ?>
                        </td>
                        <td>
                            <?= date('m-d H:i', $vo->created_at) ?>
                        </td>
                        <td>
                            <?= date('m-d H:i', $vo->updated_at) ?>
                        </td>

                        <td class="hidden-480">
                            <?php if ($vo->status == $vo::STATUS_ACTIVE): ?>
                                <span class="label label-sm label-success">显示</span>
                            <?php else: ?>
                                <span class="label label-sm label-warning">隐藏</span>
                            <?php endif;?>
                        </td>

                        <!--<td>
                            <div class="hidden-sm hidden-xs btn-group">
                                <button class="btn btn-xs btn-success">
                                    <i class="ace-icon fa fa-check bigger-120"></i>
                                </button>

                                <button class="btn btn-xs btn-info">
                                    <i class="ace-icon fa fa-pencil bigger-120"></i>
                                </button>

                                <button class="btn btn-xs btn-danger">
                                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                </button>

                                <button class="btn btn-xs btn-warning">
                                    <i class="ace-icon fa fa-flag bigger-120"></i>
                                </button>
                            </div>
                        </td>-->
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </form>

        <?= \yii\widgets\LinkPager::widget(['pagination' => $pages]); ?>
    </div>
    <!-- /.span -->
</div>