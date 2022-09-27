<?php
$o = Objects::get_sitemap();
if(!empty($o)) {
	$sm[] = '<?xml version="1.0" encoding="UTF-8"?>';
	$sm[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
	foreach( $o as $i ) {
		$sm[] = '<url><loc>' . $app_url . 'noclegi/' . Kernel::rewrite($i['city']) . '/' . Kernel::rewrite($i['name']) . '-i' . $i['id'] . '</loc><lastmod>' . date('Y-m-d') . '</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
	}
	
	$sm[] = '</urlset>';
	
	file_put_contents($app_path . 'sitemap.xml', implode(PHP_EOL , $sm));
}