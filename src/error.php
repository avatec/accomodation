<?php
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

 Kernel::schema("error");

 $smarty->assign("tpl" , $tpl = Kernel::getTpl());
 $smarty->assign("error" , $route->path);
 $smarty->display($app_path . "templates/errors/index.smarty");
 exit;
