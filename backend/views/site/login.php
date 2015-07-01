<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
$web_path = \wanhunet\wanhunet::getAlias("@web") . "/";
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta charset="utf-8"/>
    <title>Login Page - wanhunet</title>

    <meta name="description" content="User login page"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="<?= $web_path ?>assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?= $web_path ?>assets/font-awesome/4.2.0/css/font-awesome.min.css"/>

    <!-- text fonts -->
    <link rel="stylesheet" href="<?= $web_path ?>assets/fonts/fonts.googleapis.com.css"/>

    <!-- ace styles -->
    <link rel="stylesheet" href="<?= $web_path ?>assets/css/ace.min.css"/>

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="<?=$web_path?>assets/css/ace-part2.min.css"/>
    <![endif]-->
    <link rel="stylesheet" href="<?= $web_path ?>assets/css/ace-rtl.min.css"/>

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="<?=$web_path?>assets/css/ace-ie.min.css"/>
    <![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script src="<?=$web_path?>assets/js/html5shiv.min.js"></script>
    <script src="<?=$web_path?>assets/js/respond.min.js"></script>
    <![endif]-->
</head>

<body class="login-layout">
<style>
    *{
        font-family: '微软雅黑';
    }
</style>
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    <div class="center">
                        <h1 style="display: inline-block;">
                            <span class="red">万虎网络</span>
                        </h1>
                    </div>

                    <div class="space-6"></div>

                    <div class="position-relative">
                        <div id="login-box" class="login-box visible widget-box no-border">
                            <div class="widget-body">
                                <div class="widget-main">
                                    <h4 class="header blue lighter bigger" style="font-family: '微软雅黑';font-size: 16px;">
                                        <i class="ace-icon fa fa-coffee green"></i>
                                        请输入您的信息
                                    </h4>

                                    <div class="space-6"></div>

                                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                                    <fieldset>
                                        <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<?= $form->field($model, 'username') ?>
                                                            <i class="ace-icon fa fa-user"></i>
														</span>
                                        </label>

                                        <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<?= $form->field($model, 'password')->passwordInput() ?>
                                                            <i class="ace-icon fa fa-lock"></i>
														</span>
                                        </label>

                                        <div class="space"></div>

                                        <div class="clearfix">
                                            <label class="inline">
                                                <?= $form->field($model, 'rememberMe')->checkbox() ?>
                                            </label>
                                            <div style="float: right;">
                                                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                                            </div>
                                        </div>

                                        <div class="space-4"></div>
                                    </fieldset>
                                    <?php ActiveForm::end(); ?>

                                </div>
                                <!-- /.widget-main -->

                                <div class="toolbar clearfix">

                                </div>
                            </div>
                            <!-- /.widget-body -->
                        </div>
                        <!-- /.login-box -->
                    </div>
                    <!-- /.position-relative -->

                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.main-content -->
</div>
<!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->
<script src="<?= $web_path ?>assets/js/jquery.2.1.1.min.js"></script>

<!-- <![endif]-->

<!--[if IE]>
<script src="<?=$web_path?>assets/js/jquery.1.11.1.min.js"></script>
<![endif]-->

<!--[if !IE]> -->
<script type="text/javascript">
    window.jQuery || document.write("<script src='<?=$web_path?>assets/js/jquery.min.js'>" + "<" + "/script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='<?=$web_path?>assets/js/jquery1x.min.js'>" + "<" + "/script>");
</script>
<![endif]-->
<script type="text/javascript">
    if ('ontouchstart' in document.documentElement) document.write("<script src='<?=$web_path?>assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
</script>

</body>
</html>

