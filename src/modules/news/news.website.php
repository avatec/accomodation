<?php
Kernel::module("news");
Kernel::schema("news");

$smarty->assign("category" , $NewsCategory->get());

$NewsArray = [
	'years'		=> $news->getArchive(),
	'archive' 	=> $news->getPaginatedArchive( date('Y', strtotime('-1 year')) ),
	'category' 	=> $NewsCategory->get(),
	'main' 		=> $news->main(),
	'list' 		=> $news->getPaginated()
];

switch($command) {
	case "archive":
		Kernel::template("archive.smarty");
		$NewsArray = [
			'years'		=> $news->getArchive(),
			'archive' 	=> $news->getPaginatedArchive( $id ),
			'category' 	=> $NewsCategory->get(),
			'main' 		=> $news->main(),
			'list' 		=> $news->getPaginated()
		];

		$smarty->assign("content" , $Pages = array("id" => 99, "name" => "Aktualności", "parent" => 0, "rewrite" => "news"));
		Kernel::addPath([ 'name' => 'Aktualności', 'url' => '/aktualnosci', 'main' => false] );
		Kernel::addPath([ 'name' => $id, 'url' => null, 'main' => true] );
		
		Kernel::addMeta( 
			'Archiwum aktualności - ' . $config['service_meta_title'], 
			(empty($Pages['meta_desc']) ? $config['service_meta_description'] : $Pages['meta_desc']), 
			(empty($Pages['meta_keys']) ? $config['service_meta_keywords'] : $Pages['meta_keys']), 
			(empty($Pages['meta_index']) ? 'index' : $Pages['meta_index']), 
			(empty($Pages['meta_follow']) ? 'follow' : $Pages['meta_follow'])
		);
	break;
	
	case "list":
		Kernel::template("list.smarty");
		$smarty->assign("list" , $news->getPaginated(5));
		
		Kernel::addMeta( 
			'Aktualności - ' . $config['service_meta_title'], 
			(empty($Pages['meta_desc']) ? $config['service_meta_description'] : $Pages['meta_desc']), 
			(empty($Pages['meta_keys']) ? $config['service_meta_keywords'] : $Pages['meta_keys']), 
			(empty($Pages['meta_index']) ? 'index' : $Pages['meta_index']), 
			(empty($Pages['meta_follow']) ? 'follow' : $Pages['meta_follow'])
		);
	break;
	
	case "list-by-category":
		Kernel::template("list.smarty");
		$NewsArray = [
			'years'		=> $news->getArchive(),
			'archive' 	=> $news->getPaginatedArchive( date('Y', strtotime('-1 year')) ),
			'category' 	=> $NewsCategory->get(),
			'main' 		=> $news->main(),
			'list' 		=> $news->getByCategory($id, true)
		];
		$smarty->assign("list" , $NewsArray );
		
		Kernel::addMeta( 
			'Aktualności - ' . $config['service_meta_title'], 
			(empty($Pages['meta_desc']) ? $config['service_meta_description'] : $Pages['meta_desc']), 
			(empty($Pages['meta_keys']) ? $config['service_meta_keywords'] : $Pages['meta_keys']), 
			(empty($Pages['meta_index']) ? 'index' : $Pages['meta_index']), 
			(empty($Pages['meta_follow']) ? 'follow' : $Pages['meta_follow'])
		);
	break;
	
	case "view":
		Kernel::setCss("magnificpopup.css" , null);
		Kernel::setJs("magnificpopup.min.js" , null);
		Kernel::setJs("view.js" , "objects");
		Kernel::template("view.smarty");
		
		$Row = $news->get( $id );
		$smarty->assign("view" , $Row);
		$smarty->assign("content" , $Pages = array("id" => 99, "parent" => 0, "rewrite" => "aktualnosci"));
		
		$smarty->assign("gallery" , $news_gallery->getUsingNewsId( $id ));

		Kernel::addMeta( 
			$Row['topic'] . ' - Aktualności - ' . $config['service_meta_title'], 
			(empty($Pages['meta_desc']) ? $config['service_meta_description'] : $Pages['meta_desc']), 
			(empty($Pages['meta_keys']) ? $config['service_meta_keywords'] : $Pages['meta_keys']), 
			(empty($Pages['meta_index']) ? 'index' : $Pages['meta_index']), 
			(empty($Pages['meta_follow']) ? 'follow' : $Pages['meta_follow'])
		);
	break;	
}

$smarty->assign("news" , $NewsArray);