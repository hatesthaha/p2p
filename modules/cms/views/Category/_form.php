<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modules\cms\models\Category */
/* @var $form yii\widgets\ActiveForm */
$categorys = \modules\cms\models\Category::eachCategoryTree();
?>

<div class="category-form col-xs-8">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'parent')->dropDownList($categorys['data']) ?>
    <?= $form->field($model, 'status')->dropDownList([\modules\cms\models\Category::STATUS_ACTIVE => "显示", \modules\cms\models\Category::STATUS_DELETED => "隐藏"]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '保存' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
