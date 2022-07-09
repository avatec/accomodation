<?php
use Framework\Kernel, Framework\Form;

Kernel::schema("jobs");	
Kernel::module("jobs");
Content::$submenu = true;

Kernel::addMeta( 
	(empty($Pages['meta_title']) ? $config['service_meta_title'] : $Pages['meta_title']), 
	(empty($Pages['meta_desc']) ? $config['service_meta_description'] : $Pages['meta_desc']), 
	(empty($Pages['meta_keys']) ? $config['service_meta_keywords'] : $Pages['meta_keys']), 
	(empty($Pages['meta_index']) ? 'index' : $Pages['meta_index']), 
	(empty($Pages['meta_follow']) ? 'follow' : $Pages['meta_follow'])
);

switch($command) {
	case "list":
		Kernel::template("list");
		$smarty->assign("list" , $jobs->get());
	break;
	
	case "view":
		preg_match("/\j([0-9]+)\-/" , $id, $matches);
		if(is_numeric($matches[1])) {
			$job_id = $matches[1];
			Kernel::template("details");
			$smarty->assign("view" , $jobs->get($job_id));
		}
	break;
	
	case "apply":
		Kernel::template("form");
		$smarty->assign("list" , $jobs->get());
		$smarty->assign("errorNotice" , Kernel::getMessage("ERROR"));
		
		if(isset($_GET['offer'])) {
			$job = $jobs->get($_GET['offer']);
			Form::$post['position'] = $job['name'];
		}
					
		if(!empty($request->post['module'])) {
			$result = $jobs->send();
			if( $result == false ) {
				Kernel::setMessage("ERROR" , Jobs::$Error);
			} else {
				$smarty->assign("result" , true);
			}
		}
	break;
}