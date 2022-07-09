<?php
Kernel::schema("main");

$smarty->assign("news" , $news->last(3, true));
$smarty->assign("partners" , $partner->getForCarousel());
$smarty->assign("promoted" , $objects->getPromoted("MAIN",(!empty($config['promoted_main_amount']) ? $config['promoted_main_amount'] : 3)));
$smarty->assign("shortcuts" , $location->getMain());
if(!isset($config['basic'])) {
	$smarty->assign("special" , $special->getMain());
}

Kernel::setJs("rc.min.js");
Kernel::setJs("hammer/hammer.min.js");
Kernel::setJs("hammer/jquery.hammer.min.js");
Kernel::setJs("main.js");



