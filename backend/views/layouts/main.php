<?php
use backend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$web_path = Yii::getAlias('@web') . '/';
$layout_path = dirname(__file__);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>

    <?= Html::csrfMetaTags() ?>

    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head() ?>
    <?php include $layout_path . "/head.php"; ?>
    <?php if (isset($this->blocks['inline_styles'])): ?>
        <?= $this->blocks['inline_styles'] ?>
    <?php endif; ?>
</head>
<body class="no-skin">
<style>
    * {
        font-family: '微软雅黑';
    }

    h1, h2, h3, h4, h5, p, span, div, li, a, ul {
        font-family: '微软雅黑';
    }
</style>
<?php $this->beginBody() ?>
<?php include $layout_path . "/navbar.php"; ?>

<div class="main-container" id="main-container">
    <script type="text/javascript">
        try {
            ace.settings.check('main-container', 'fixed')
        } catch (e) {
        }
    </script>

    <?php include $layout_path . "/sidebar.php"; ?>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs" id="breadcrumbs">
                <script type="text/javascript">
                    try {
                        ace.settings.check('breadcrumbs', 'fixed')
                    } catch (e) {
                    }
                </script>

                <!-- /.breadcrumb 面包屑部分开始 -->
                <ul class="breadcrumb">
                    <?php if (isset($this->params["breadcrumb"])): ?>
                        <?php foreach ($this->params["breadcrumb"] as $key => $breadcrumb): ?>
                            <?php if ($key == count($this->params["breadcrumb"]) - 1) { ?>
                                <li class="active"><?= $breadcrumb[0] ?></li>
                            <?php } elseif ($key == 0) { ?>
                                <li>
                                    <i class="ace-icon fa fa-home home-icon"></i>
                                    <a href="<?= $breadcrumb[1] ?>"><?= $breadcrumb[0] ?></a>
                                </li>
                            <?php } else { ?>
                                <li>
                                    <a href="<?= $breadcrumb[1] ?>"><?= $breadcrumb[0] ?></a>
                                </li>
                            <?php } ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
                <!-- /.breadcrumb 面包屑部分结束 -->

            </div>
            <div class="page-content">
                <?php if (isset($this->params["page_header"])): ?>
                    <div class="page-header">
                        <h1>
                            <?= $this->params["page_header"][0] ?>
                            <small>
                                <i class="ace-icon fa fa-angle-double-right"></i>
                                <?= $this->params["page_header"][1] ?>
                            </small>
                        </h1>
                    </div>
                <?php endif; ?>
                <!-- /.page-header -->
                <?= $content ?>
            </div>
        </div>
    </div>
    <!-- /.main-content -->
    <div class="footer">
        <div class="footer-inner">
            <div class="footer-content">
			    <span class="bigger-120">
			    	<span class="blue bolder">万虎网络</span>
			    	Application &copy; 2014-2015
			    </span>
            </div>
        </div>
    </div>

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>
</div>

<?php include $layout_path . "/basic_scripts.php"; ?>

<?php if (isset($this->blocks['inline_scripts'])): ?>
    <?= $this->blocks['inline_scripts'] ?>
<?php endif; ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
