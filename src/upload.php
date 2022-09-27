<?php
/**
 *	Autoloader dla nowych modułów
 * 	Obsługujący wszystkie moduły dzielone
 */
	if( isset($route->path_array[1]) && ($route->path_array[1] == "admin") ) {
		Kernel::log(
			'upload.log' , 'Starting admin uploader...'
		);

		$mod_dir = scandir( $app_path . 'modules/' );
		if(!empty( $mod_dir )) {
			foreach( $mod_dir as $mod_file ) {
				if( System::file_exists( $app_path . 'modules/' . $mod_file . '/backend/autoloader.php') == true ) {
					require_once $app_path . "modules/" . $mod_file . "/backend/autoloader.php";
				}
			}
		}
	}



if( !function_exists('apache_request_headers') ) {
	function apache_request_headers() {
		$arh = array();
		$rx_http = '/\AHTTP_/';
		foreach($_SERVER as $key => $val) {
			if( preg_match($rx_http, $key) ) {
				$arh_key = preg_replace($rx_http, '', $key);
				$rx_matches = array();
				$rx_matches = explode('_', $arh_key);
				if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
					foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
					$arh_key = implode('-', $rx_matches);
				}
				$arh[$arh_key] = $val;
			}
		}
		return( $arh );
	}
}

/**
 *	Obsługa UPLOADU
 */

	$headers = apache_request_headers();
	Kernel::log('upload.log', 'Headers: ' . PHP_EOL . print_r( $headers, true ));
	foreach( $headers as $key=>$item ) {
		$headers[strtoupper($key)] = $item;
	}
	if( empty($_FILES['upload'])) {
		$json = [ 'error' => true, 'msg' => 'Cannot find $_FILES[\'upload\']', 'error_code' => 100 ];
		Kernel::log('upload.log', 'ERROR ! ' . PHP_EOL . json_encode( $json ));
		die( json_encode( $json ));
	}

	Kernel::log('upload.log', 'Ok headers passed ! ' . PHP_EOL);

/**
 *	Sprawdzenie czy uploadowany plik zawiera akceptowany format mime
 */

 if(!empty($headers['ACCEPT-FILETYPE'])) {

	 if(!empty($_FILES['upload']['tmp_name'])) {
		 $mime = mime_content_type( $_FILES['upload']['tmp_name'] );

		 $accepted = explode("," , str_replace(" " , "", $headers['ACCEPT-FILETYPE']));
		 $r = in_array($mime, $accepted);

		 if( $r == false ) {
			 foreach( $accepted as $mime_check ) {
				 $mime_1 = explode("/", $mime_check);
				 $mime_2 = explode("/" , $mime);
				 if( $mime_1[0] == $mime_2[0] ) {
					 $r = true;
				 }
			 }
		 }

		 if( $r == false ) {
			 $json = [ 'error' => true, 'msg' => 'File type rejected', 'error_code' => 101 ];
			 Kernel::log(
				 'upload.log',
				 'ERROR ! ' . $json['msg'] . PHP_EOL .
				 'Received: ' . print_r($mime, true) . PHP_EOL .
				 'Accepted: ' . print_r($accepted, true)
			 );
			 die( json_encode( $json ));
		 }
	 }
 }

	Kernel::log('upload.log', 'Ok accept-filetype passed ! ' . PHP_EOL);

/**
 *	Przekazanie do uploadu w kontrolerze
 */

 	if(!empty($request->post['controller'])) {
		Kernel::log('upload.log' , 'Redirecting to controller...');

		$controller = $request->post['controller'];
		if(empty($request->post['method'])) {
			if( method_exists( $controller, 'LiveUploader' ) == false ) {
				Kernel::log('upload.log' , $controller . '::LiveUploader not found');
				$json = [ 'error' => true, 'msg' => 'Method ' . $controller . '::LiveUploader not found', 'error_code' => 404];
				die( json_encode( $json ) );
			}
			$json = $controller::LiveUploader( $_FILES['upload'] );
			Kernel::log('upload.log', $controller . '::LiveUploader returns: ' . json_encode( $json ));
		} else {
			$method = $request->post['method'];
			if( method_exists( $controller, $method ) == false ) {
				Kernel::log('upload.log' , $controller . '::LiveUploader not found');
				$json = [ 'error' => true, 'msg' => 'Method ' . $controller . '::' . $method . ' not found', 'error_code' => 404];
				die( json_encode( $json ) );
			}
			$json = $controller::$method( $_FILES['upload'] );
			Kernel::log('upload.log', $controller . '::' . $method . ' returns: ' . json_encode( $json ));
		}

		die( json_encode( $json ) );
		exit;
	}

/**
 *	Przypisywanie katalogu uploadu
 */

 	Kernel::log('upload.log', 'Is admin: ' . var_export($route->isAdmin, true) . PHP_EOL);
 	Modules\Admins::restore();

 	if(!empty( Modules\Admins::$auth['id'] )) {
	 	$upload_user_dir = $app_path . "cache/temp/" . Modules\Admins::$auth['id'] . "/";
 	} else {
	 	Modules\Users::restore();
	 	$upload_user_dir = $app_path . "cache/temp/" . Modules\Users::$auth['id'] . "/";
 	}

	Kernel::log('upload.log', 'Upload user dir: ' . $upload_user_dir . PHP_EOL);

/**
 * 	Tworzenie katalogu jeżeli nie istnieje
 */

	if(file_exists($upload_user_dir) == false) {
		mkdir( $upload_user_dir, 0777 );
	}

	Kernel::log('upload.log', 'Dir create passed' . PHP_EOL);

/**
 *	Przeprowadzanie uploadu
 */

	$result = System::upload( $_FILES['upload'] , [
		'upload_dir' => $upload_user_dir,
		'thumbs' => false,
		'thumb_width' => 384,
		'thumb_height' => 384
	]);
	Kernel::log('upload.log', 'Result:' . print_r($result, true) . PHP_EOL);
	Kernel::log('upload.log' , 'Info: ' . str_replace($app_path, $app_url, $upload_user_dir) . $result);

	if(!empty($request->post['ckCsrfToken'])) {
		rename( $upload_user_dir . $result, $app_path . 'userfiles/editor/' . $result );
		$json_response = json_encode([
			'uploaded' =>  true,
			'url' => '/userfiles/editor/' . $result
		]);

		Kernel::log('upload.log' , 'Response: ' . PHP_EOL . $json_response);
		die( $json_response );
	}



	if( $result !== false ) {
		$_SESSION['uploaded_files'][] = [
			'path' => $upload_user_dir . $result,
			'url' => str_replace($app_path, $app_url, $upload_user_dir),
			'file' => $result
		];

		$json = [
			'success' => true,
			'file' => $result,
			'url' => str_replace($app_path, $app_url, $upload_user_dir . $result)
		];

	} else {

		$json = [
			'error' => true,
			'msg' => 'File ' . $result . ' could not be uploaded due error',
			'error_code' => '102',
			'file' => $result

		];
	}

	Kernel::log('upload.log', 'Result' . PHP_EOL . json_encode( $json ));

	die( json_encode( $json ) );
