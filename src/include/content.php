<?php
global $Pages;

if($route->isMain == true) {
	$Pages = $content->getMain();
} else {
	$path = ltrim($route->path, '/');
	$Pages = $content->getByPath( $path );
}

if(!empty($Pages)) {
	if(!empty($Pages['redirect'])) {
		$r = $content->getLink( $Pages['redirect'] );
		Kernel::redirect( $app_url . $r );
	}

	$Pages['text'] = html_entity_decode( $Pages['text'] );

	if($Pages['main'] == "TRUE") {
		$route->isMain = true;
		if(file_exists( $app_path . "include/mods/index.php")) {
			include $app_path . "include/mods/index.php";
		}
	} else {
		$route->isMain = false;
		Kernel::schema("content");
		if($Pages['parent'] == 0) {
			Kernel::addPath(array(
				'name' => $Pages['name'],
				'url' => $app_url . $Pages['rewrite'],
				'main' => true
			));
			$smarty->assign("submenu" , $content->getSubmenu($Pages['id'], 0, true));
		} else {
			$smarty->assign("submenu" , $content->getSubmenu($Pages['parent'], 0, true));

			Kernel::addPath(array(
				'name' => $content->getParentName($Pages['parent'],false),
				'url' => $app_url . $content->getLink($Pages['parent']),
				'main' => false
			));

			Kernel::addPath(array(
				'name' => $Pages['name'],
				'main' => true
			));
		}
	}

	if(!is_null($Pages['component'])) {
		preg_match('$\[([0-9]+)\]$' , $Pages['component'], $matches);
		if(!empty($matches)) {
			$component = preg_replace('$\[([0-9]+)\]$' , '', $Pages['component']);
			$component_id = preg_replace('$\[([0-9]+)\]$' , '$1', $matches['0']);
		} else {
			$component = $Pages['component'];
		}

		$c = explode("/" , $component);
		if(!empty($c[1])) {
			$component = $c[0];
			$action = $c[1];
			unset($c);
			$c = explode(":" , $action);
			if(!empty($c)) {
				$action = $c[0];
				if(!empty($c[1])) {
					$id = $c[1];
				}
			}
		}
		Kernel::schema( $component );
		if( file_exists( $app_path . "include/mods/" . $component . ".php")) {
			include_once $app_path . "include/mods/" . $component . ".php";
		} else {
			$command = (empty($action) ? "index" : $action);

			// Nowe moduły
			if( System::file_exists( $app_path . 'modules/' . $component . '/frontend/' . $component. '.frontend.php' ) == true ) {
				include_once $app_path . 'modules/' . $component . '/frontend/' . $component. '.frontend.php';
			} else {
				include_once $app_path . "modules/" . $component . "/" . $component . ".website.php";
			}
		}
	}

	Kernel::addMeta(
		(empty($Pages['meta_title']) ? $config['service_meta_title'] : $Pages['meta_title']),
		(empty($Pages['meta_desc']) ? $config['service_meta_description'] : $Pages['meta_desc']),
		(empty($Pages['meta_keys']) ? $config['service_meta_keywords'] : $Pages['meta_keys']),
		(empty($Pages['meta_index']) ? 'index' : $Pages['meta_index']),
		(empty($Pages['meta_follow']) ? 'follow' : $Pages['meta_follow'])
	);

	$smarty->assign("content" , $Pages);
}  else {
	if(!empty($route->results))
	{
		$command 	= $route->results['command'];
		$module 	= $route->results['module'];
		$file 		= $route->results['file'];
		$mirror 	= (!empty($route->results['mirror']) ? true : false);
		$id 		= (!empty($route->results['id']) ? $route->results['id'] : null);

		// Nowy system modułów
		if( System::file_exists( $app_path . 'modules/' . $module . '/frontend/' . $file . '.frontend.php') == true ) {
			include_once $app_path . 'modules/' . $module . '/frontend/' . $file . '.frontend.php';
		} else {
			include_once $route->results['url'];
		}
	} else {
		http_response_code(404);
		include $app_path . 'error.php';
	}
}
