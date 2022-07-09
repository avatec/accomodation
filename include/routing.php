<?php
// TODO: Deprecated
/*
 | Routing dla panelu użytkownika
 |
 | $route->get( $regexp, [ $schema, $module, $command ]
 |
 | $regexp - wyrażenie regularne
 | $schema - określenie paramentru $command np. dla (panel)\/:string pobierać jako command mamy :string więc wpisujemy że znajduje się na 2 pozycji
 | $module - odniesienie do modułu - np. system określa plik system/system.website.php, jeżeli podamy system.users określa system/users.website.php
 | $command - określenie polecenia do wywołania, gdy ustawimy ten parametr schema określa położenie parametru $id
 */

$route->get(
	'(language)-:string',
	[ 'schema' => '2', 'module' => 'system', 'command' => 'change-language' ]
);
/**
$route->get(
	'(panel)\/:string',
	[ 'schema' => '2', 'module' => 'system.users' ]
);
$route->get(
	'(panel)\/(activate)\/:string',
	[ 'schema' => '3', 'module' => 'system', 'file' => 'users', 'command' => 'activate' ]
);
$route->get(
	'(panel)\/(objects)\/:string',
	[ 'schema' => '3', 'module' => 'objects' ]
);
$route->get(
	'(panel)\/(objects)\/(photos)\/:string',
	[ 'schema' => '4', 'module' => 'objects', 'file' => 'photos' ]
);
$route->get(
	'(panel)\/(objects)\/(video)\/:string',
	[ 'schema' => '4', 'module' => 'objects', 'file' => 'video' ]
);
**/

/**
$route->get(
	'(panel)\/(objects)\/(rooms)\/:string',
	[ 'schema' => '4', 'module' => 'objects', 'file' => 'rooms' ]
);
$route->get(
	'(panel)\/(objects)\/(comments)\/:string',
	[ 'schema' => '4', 'module' => 'objects', 'file' => 'comments' ]
);

$route->get(
	'(panel)\/(rooms)\/(photos)\/:string',
	[ 'schema' => '4', 'module' => 'objects', 'file' => 'photosroom' ]
);
$route->get(
	'(panel)\/(booking)\/:string',
	[ 'schema' => '3', 'module' => 'booking' ]
);
**/
/**
 * Oferty specjalne zarządzanie
 */
/**
$route->get(
	'(panel)\/(special)\/:string',
	[ 'schema' => '3', 'module' => 'special' ]
);
**/

/*
 | Płatności
 */
/**
$route->get(
	'(payments)\/:string',
	[ 'schema' => '2', 'module' => 'payment', 'file' => 'payment' ]
);
**/

/*
 | Newsy
 */
/**
$route->get(
	'(news)\/(view)\/:string-i:id',
	[ 'schema' => '4', 'module' => 'news', 'command' => 'view' ]
);

$route->get(
	'(news)\/:string-c:id',
	[ 'schema' => '3', 'module' => 'news', 'command' => 'list-by-category' ]
);
**/
/*
 | Wyszukiwarka
 */
/**
$route->get(
	'(search)',
	[ 'module' => 'objects', 'command' => 'search' ]
);

$route->get(
	'(search)\/:string',
	[ 'schema' => 2, 'module' => 'objects', 'command' => 'search' ]
);

$route->get(
	'(search)\/(special)\/:string',
	[ 'schema' => 3, 'module' => 'objects', 'command' => 'list-by-special' ]
);
**/
/*
 | Routing podglądu obiektu
 */
/**
$route->get(
	'noclegi\/:string',
	[ 'schema' => 1, 'module' => 'objects.objects' , 'command' => 'list-by-city' ]
);

$route->get(
	'noclegi\/:string\/:string-i:id',
	[ 'schema' => 3, 'module' => 'objects.objects' , 'command' => 'view' ]
);
**/
/*
 | Routing dla booking
 */
/**
$route->get(
	'booking\/check\/o:id',
	[ 'schema' => '1', 'module' => 'booking.booking' , 'command' => 'check' ]
);

$route->get(
	'booking\/check\/o:id-r:id',
	[ 'schema' => "1,2", 'module' => 'booking.booking' , 'command' => 'check' ]
);

$route->get(
	'booking\/finish',
	[ 'module' => 'booking.booking', 'command' => 'finish' ]
);

$route->get(
	'booking\/payment-verify',
	[ 'module' => 'booking.booking', 'command' => 'payment-verify' ]
);

$route->get(
	'booking\/payment-finish',
	[ 'module' => 'booking.booking', 'command' => 'payment-finish' ]
);
**/
/*
 | Routing dla newsletter
 */

/**
$route->get(
	'newsletter\/activate\/:string',
	[ 'schema' => 1, 'module' => 'newsletter.newsletter' , 'command' => 'activate' ]
);
$route->get(
	'newsletter\/unsubscribe\/:string',
	[ 'schema' => 1, 'module' => 'newsletter.newsletter' , 'command' => 'unsubscribe' ]
);
**/

/*
 | Obsługa błędów
 */

$route->get(
	'(error)-:id\.html' ,
	[ 'schema' => 2, 'module' => 'system' , 'command' => 'error' ]
);
