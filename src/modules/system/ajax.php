<?php
switch($command)
{
	case "stats":
		$result = Stats::get( $request->get['action'] );
		if(!empty($result)) {
			die( json_encode($result) );
		}
	break;
}