<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model modules\cms\models\post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'content:html',
            ['label' => '类别', 'value' => $model->category->title],
            ['label' => '状态', 'value' => ($model->status == $model::STATUS_ACTIVE ? "显示" : "隐藏")],
            ['label' => '创建时间', 'value' => date('Y-m-d H:i:s', $model->created_at)],
            ['label' => '更新时间', 'value' => date('Y-m-d H:i:s', $model->updated_at)],
        ],
    ]) ?>

</div>
