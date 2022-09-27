<?php
Kernel::module("news");
Kernel::schema("news");
Kernel::template("list.smarty");

$NewsArray = [
	'years'		=> $news->getArchive(),
	'archive' 	=> $news->getPaginatedArchive( date('Y', strtotime('-1 year')) ),
	'category' 	=> $NewsCategory->get(),
	'main' 		=> $news->main(),
	'list' 		=> $news->getPaginated()
];

$smarty->assign("news" , $NewsArray);
$smarty->assign("category" , $NewsCategory->get());
$smarty->assign("partners" , $partner->getForCarousel());