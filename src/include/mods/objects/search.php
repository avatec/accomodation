<?php
Kernel::$CheckBox = true;
Kernel::schema("objects/search");
Kernel::setJs("search.js" , "objects");
$smarty->assign("results" , $objects->search());
if(!empty(Objects::$countedResults)) {
	$smarty->assign("counted_results" , Objects::$countedResults);
} else {
	$smarty->assign("counted_results" , 0);
}

$smarty->assign("distance" , $distance->get());
$smarty->assign("improvement" , $improvement->get());

$smarty->assign("promoted" , $objects->getPromoted("MAIN",(!empty($config['promoted_main_amount']) ? $config['promoted_main_amount'] : 3)));