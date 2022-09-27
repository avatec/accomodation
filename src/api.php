<?php
use \Modules\Admins;

header('Content-type: application/json');

if( $route->path_array[0] == 'api' ) {
    unset( $route->path_array[0] );
    $route->path_array = array_values( $route->path_array );

    if( $route->path_array[1] == 'frontend' ) {
        $mod_dir = scandir( $app_path . 'modules/' );
    	if(!empty( $mod_dir )) {
    		foreach( $mod_dir as $mod_file ) {
    			if( System::file_exists( $app_path . 'modules/' . $mod_file . '/frontend/autoloader.php') == true ) {
    				require_once $app_path . "modules/" . $mod_file . "/frontend/autoloader.php";
    			}
    		}
    	}

        $method = "Api_" . ucfirst( end( $route->path_array) );
        array_pop( $route->path_array );

        $class = "\\Modules\\" . implode("\\" , $route->path_array );

        if( class_exists( $class )) {
            $api_class = new $class();
            $json = $api_class->{$method}();

            die(json_encode( $json ));
        } else {
            die(json_encode(['error' => true, 'msg' => 'Method ' . $class . '::' . $method . ' not exists in current namespace']));
        }
    }

    if( $route->path_array[1] == 'backend' ) {

    	$mod_dir = scandir( $app_path . 'modules/' );
    	if(!empty( $mod_dir )) {
    		foreach( $mod_dir as $mod_file ) {
    			if( System::file_exists( $app_path . 'modules/' . $mod_file . '/backend/autoloader.php') == true ) {
    				require_once $app_path . "modules/" . $mod_file . "/backend/autoloader.php";
    			}
    		}
    	}

        Admins::restore();

        $method = "Api_" . ucfirst( end( $route->path_array) );
        array_pop( $route->path_array );

        $class = "\\Modules\\" . implode("\\" , $route->path_array );

        if( class_exists( $class )) {
            $api_class = new $class();
            $json = $api_class->{$method}();

            die(json_encode( $json ));
        } else {
            die(json_encode(['error' => true, 'msg' => 'Method ' . $class . '::' . $method . ' not exists in current namespace']));
        }
    }
}

if( !empty($json) && !is_array( $json )) {
    die( json_encode( $json ) );
}

die( json_encode([ 'error' => true, 'msg' => 'Empty request' ]) );
