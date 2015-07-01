<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */
/* @var $this \yii\web\View */
$sidebar = $this->params['sidebar'];
?>
<div id="sidebar" class="sidebar responsive">
    <script type="text/javascript">
        try {
            ace.settings.check('sidebar', 'fixed')
        } catch (e) {
        }
    </script>

    <div class="sidebar-shortcuts" id="sidebar-shortcuts">
        <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
            <button class="btn btn-success">
                <i class="ace-icon fa fa-signal"></i>
            </button>

            <button class="btn btn-info">
                <i class="ace-icon fa fa-pencil"></i>
            </button>

            <button class="btn btn-warning">
                <i class="ace-icon fa fa-users"></i>
            </button>

            <button class="btn btn-danger">
                <i class="ace-icon fa fa-cogs"></i>
            </button>
        </div>

        <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
            <span class="btn btn-success"></span>

            <span class="btn btn-info"></span>

            <span class="btn btn-warning"></span>

            <span class="btn btn-danger"></span>
        </div>
    </div>
    <!-- /.sidebar-shortcuts -->
    <?php
    $r = \wanhunet\wanhunet::$app->controller->getRoute();
    $nav_tpl_start = <<<HTML
    <li class="">
        <a href="{{route}}" class="dropdown-toggle">
            <span class="menu-text"> {{title}} </span>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">\n
HTML;

    $nav_tpl_end = <<<HTML
        </ul>
    </li>\n
HTML;

    $nav_alink = <<<HTML
        <li class="{{active}}">
			<a href="{{route}}">
				{{title}}
			</a>
			<b class="arrow"></b>
		</li>\n
HTML;


    ?>
    <ul class="nav nav-list">
        <?php
        function buildMenus(&$datas, $nav_tpl_start, $nav_tpl_end, $nav_alink, $r)
        {
            foreach ($datas as $key => &$data) {
                if (isset($data['child']) or !is_string($data[0])) {
                    if (isset($data['child'])) {
                        $next =& $datas[$key]['child'];
                    } else {
                        $next =& $datas[$key];
                    }
                    if (is_string($data[0]) and count($next) > 0) {
                        echo str_replace('{{route}}', "", str_replace('{{title}}', $data[0], $nav_tpl_start));
                    }

                    buildMenus($next, $nav_tpl_start, $nav_tpl_end, $nav_alink, $r);

                    if (is_string($data[0]) and count($next) > 0) {
                        echo $nav_tpl_end;
                    }
                } else {
                    $item = str_replace('{{route}}', \yii\helpers\Url::to(['/' . $data[1]]), str_replace('{{title}}', $data[0], $nav_alink));
                    if ($r == $data[1]) {
                        $item = str_replace("{{active}}", "active", $item);
                    }
                    echo $item;
                }
            }
        }

        ?>
        <?php
        buildMenus($sidebar, $nav_tpl_start, $nav_tpl_end, $nav_alink, $r);
        ?>
        <!--<li class="">
            <a href="javascript:" class="dropdown-toggle">
                <i class="menu-icon"></i>
                <span class="menu-text"> title1 </span>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">

            </ul>
        </li>-->

    </ul>
    <!-- /.nav-list -->
    <script>
        $(document).ready(function () {
            var item = $("li.active").parent("li");
//            console.log($(item));
            item.addClass('active open');
        });
    </script>

    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <!--<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left"
           data-icon2="ace-icon fa fa-angle-double-right"></i>-->
    </div>

    <script type="text/javascript">
        try {
            ace.settings.check('sidebar', 'collapsed')
        } catch (e) {
        }
    </script>
</div>
