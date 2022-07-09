<?php
use Modules\Admins as Admins;
use Modules\Admins\Tokens as AdminsTokens;

/**
 * Avatec Accomodation
 *
 * @author		Grzegorz Miskiewicz <biuro@avatec.pl>
 * @license		Property license
 *
 * Ten produkt jest licencjonowany
 * Możesz modyfikować te pliki jednak nie możesz usuwać oryginalnych komentarzy
 * w szczególności informacji o autorze tego oprogramowania<
 *
 * W przypadku indywidualnego modyfikowania tego pliku autor nie ponosi
 * odpowiedzialności za wszelkie błędy i/lub wady tego oprogramowania.
 */

 header('Content-type: application/json');
 if ($route->path_array['0'] == "ajax") {
     if( $route->path_array['1'] == "admin" ) {

         if(!empty(Admins::$auth['token'])) {
         	if( AdminsTokens::update( Admins::$auth['token'] ) == false ) {
         		$admins->logout();
         	}
         }

         $module = $route->path_array['2'];
         $command = $route->path_array['3'];
         $ajax_filename = "ajax.admin.php";
     } else {
         $module = $route->path_array['1'];
         $command = $route->path_array['2'];
         $ajax_filename = "ajax.php";
     }

     if (file_exists($app_path . "modules/" . $module . "/" . $ajax_filename) == true) {
         include_once $app_path . "modules/" . $module . "/" . $ajax_filename;

         header('Content-Type: application/json');

         if (!empty($JSON)) {
             if (is_array($JSON)) {
                 echo json_encode($JSON);
             } else {
                 echo $JSON;
             }
         }
         exit;
     } else {
         die("ajax file not found: " . $app_path . "modules/" . $module . "/" . $ajax_filename . ".php");
     }
 }
