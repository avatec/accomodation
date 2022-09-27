<?php

class Paginate
{
	public static $query;
	public static $perpage = 15;
	protected static $page;
	protected static $max_page_per_session = 10;

	public function __construct()
	{
		if(!empty($_SESSION['paginate_footer'])) {
			unset($_SESSION['paginate_footer']);
		}
	}

	public function __destruct()
	{
		if(!empty($_SESSION['paginate_footer'])) {
			unset($_SESSION['paginate_footer']);
		}
	}

    public static function make()
    {
    	global $config, $request, $request,$app_request_url;

    	if(!empty(self::$query)) {
        	$count = Db::query( self::$query );
        	$count = (!empty($count) ? count($count) : 0);
    	} else {
        	trigger_error("Paginate::make requires self::\$query to be defined");
    	}

    	if(!empty($config['service_perpage'])) {
        	self::$perpage = $config['service_perpage'];
    	}

    	if(empty(self::$page)) {
    		if(isset($request->get['page'])) {
        		self::$page = $request->get['page'];
        	} else {
	        	self::$page = 1;
        	}
    	}

    	$pages = ceil( $count/self::$perpage );
    	if($pages == 0) {
        	$pages = 1;
    	}





    	if(empty(self::$page)) {
        	$page_start = 0;
        	$page_selected = 1;
    	} else {
        	$page_start = (self::$page * self::$perpage) - self::$perpage;
    	}

    	$qs = preg_replace("!page=([0-9]+)!" , "" , $_SERVER['QUERY_STRING']);
    	$qs = preg_replace("!page=([0-9]+)[&]!" , "" , $qs);
		$url = str_replace("?" . $_SERVER['QUERY_STRING'], "" , $app_request_url);
		$url = $url . (!empty($qs) ? "?" . $qs . "&" : "?");

    	self::footer($pages, $url, $pages);

    	return Db::query(self::$query . " LIMIT " . $page_start . "," . self::$perpage);
    }

    public static function footer($pages = 20, $url = null, $max_page = null)
	{
		if(is_null($url) OR is_null($max_page)) {
			return false;
		}

		$page_start_from = self::$page;
		$page_to_view = (self::$max_page_per_session + self::$page)-1;


		if($page_start_from<=0 || $page_start_from<6) {
			$page_to_view = (self::$max_page_per_session + self::$page)-$page_start_from;
			$page_start_from = 1;
		}

		if($page_start_from >= 6) {
			$page_to_view = self::$page + 5;
			$page_start_from = $page_start_from - 5;

		}


		if( $max_page >= 10 ) {


			if($page_to_view >= $max_page) {
				$page_start_from = $max_page - self::$max_page_per_session;
				$page_to_view = $max_page;
			}

			if( $page_start_from <= 1 ) {
				$page_start_from = 1;
				$page_to_view = 10;
			}

		} else {
			$page_to_view = $max_page;
		}


		$prev_page = $url . 'page=' . ((self::$page==1) ? '1' : self::$page-1);
		$next_page = $url . 'page=' . ((self::$page>=$max_page) ? $max_page : self::$page+1);

		if(self::$page == 1) {
			$dis1 = 'class="disabled"';
		}
		if(self::$page == $max_page) {
			$dis2 = 'class="disabled"';
		}

		$html = '<div class="text-center">';
		$html .= '<ul class="pagination">';


        @$html .= '<li '.$dis1.'><span>'.(empty($dis1) ? '<a href="'.$prev_page.'">&laquo;</a>' : '&laquo').'</span></li>';
        for($i=$page_start_from;$i<=$page_to_view;$i++) {
        	if($i == self::$page) {
        		$html .= '<li class="active"><span>'.$i.'</span></li>';
        	} else {
	        	$html .= '<li><a href="'.$url . 'page=' . $i .'">'.$i.'</a></li>';
        	}
        }
        if(isset($dis1)) {
        	unset($dis1);
        }
        $html .= '<li '.(!empty($dis2) ? $dis2: '').'><span>'.(empty($dis2) ? '<a href="'.$next_page.'">&raquo;</a>' : '&raquo').'</span></li>';
		$html .= '</ul>';
		$html .= '</div>';

		if($pages>1) {
			self::set( $html );
		}
	}

	public static function set( $html )
	{
		$_SESSION['paginate_footer'] = $html;
	}

	public static function get()
	{
		if(isset($_SESSION['paginate_footer'])) {
			return $_SESSION['paginate_footer'];
		}
	}

	public static function clear()
	{
		if(!empty($_SESSION['paginate_footer'])) {
			unset($_SESSION['paginate_footer']);
		}
	}

}
