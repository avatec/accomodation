<?php
include_once $app_path . "classes/Smarty/Smarty.class.php";

class SmartyConstructor extends SmartyBC {
	public function __construct()
	{
		global $app_url, $app_path, $app_request_url, $app, $config;

		$this->cache_dir = $app_path . 'cache';
		$this->compile_dir = $app_path . 'cache/templates_c';
		$this->plugins_dir = array(
			$app_path . '/vendor/smarty/smarty/libs/plugins/'
		);

		$this->assign("app_path" , $app_path);
		$this->assign("app_url" , $app_url);
		$this->assign("app_request_url" , $app_request_url);
		if(isset($app)) { $this->assign("app" , $app); }
		$this->assign("config" , $config);
	}

	protected function show()
	{

	}
}
