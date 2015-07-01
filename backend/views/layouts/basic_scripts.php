<!-- basic scripts -->
<script src="<?=$web_path?>assets/js/jquery.2.1.1.min.js"></script>
<!--[if !IE]> -->
<script src="<?=$web_path?>assets/js/jquery.2.1.1.min.js"></script>
<!-- <![endif]-->

<!--[if IE]>
<script src="<?=$web_path?>assets/js/jquery.1.11.1.min.js"></script>
<![endif]-->

<!--[if !IE]> -->
<script type="text/javascript">
    window.jQuery || document.write("<script src='<?=$web_path?>assets/js/jquery.min.js'>"+"<"+"/script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='<?=$web_path?>assets/js/jquery1x.min.js'>"+"<"+"/script>");
</script>
<![endif]-->
<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='<?=$web_path?>assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="<?=$web_path?>assets/js/bootstrap.min.js"></script>

<!-- ace scripts -->
<script src="<?=$web_path?>assets/js/ace-elements.min.js"></script>
<script src="<?=$web_path?>assets/js/ace.min.js"></script>