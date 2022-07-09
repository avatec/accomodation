<?php
$smarty	= new Smarty();
$smarty->cache_dir = $app_path . 'cache';
$smarty->compile_dir = $app_path . 'cache/templates_c';
$smarty->plugins_dir = array(
	$app_path . '/vendor/smarty/smarty/libs/plugins/'
);

$smarty->assign("app_path" , $app_path);
$smarty->assign("app_url" , $app_url);
$smarty->assign("app_request_url" , $app_request_url);
	
if(isset($app)) { $smarty->assign("app" , $app); }
$smarty->assign("config" , $config);
