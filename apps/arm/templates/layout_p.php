<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php
$path_prefix = isset($_SERVER['PATH_PREFIX']) ? $_SERVER['PATH_PREFIX'] : '';
?>
<script type="text/javascript">
var sf_app = '<?php echo sfConfig::get('sf_app');?>';
var sf_prefix = '<?php echo $path_prefix;?>';
var sf_user = <?php echo $sf_user->isAuthenticated() ? $sf_user->getUserId() : 'false';?>;
var sf_ws_pub = '<?php echo sfConfig::get('app_ws_pub');?>';
</script>
<?php
include_http_metas();
include_stylesheets();
?>
<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/js/arm/plugins.js"></script>
<script type="text/javascript" src="/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="/js/chosen.jquery.js"></script>
<script type="text/javascript" src="/js/arm/init.d.js"></script>
<script type="text/javascript" src="/js/arm/arm.js"></script>
<script type="text/javascript" src="/js/arm/worker.js"></script>
<script type="text/javascript" src="/js/arm/bid.js"></script>
<script type="text/javascript" src="/js/arm/p2.js"></script>

<script type="text/javascript" src="/js/atmaUI.js"></script>
<title><?php include_slot('title', 'Панель мониторинга');?></title>
</head>
<body>
<div class="lui__root">
<?php
echo $sf_content;
?>
</div>
</body>
</html>