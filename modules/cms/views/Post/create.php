<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model modules\cms\models\post */

$this->title = '文章添加';
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumb'] = [
    ['内容管理', \yii\helpers\Url::to(['/cms/post/index'])],
    ['添加文章', \yii\helpers\Url::to(['/cms/post/index'])],
];
$this->params['page_header'] = ['添加文章', '用于添加文章页面'];
?>
<div class="post-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

