<?php
use Modules\Attractions\Frontend\Attractions;

/**
 *  Frontend module autoloader
 */

require_once __DIR__ . '/attractions.php';

global $attractions;
$attractions = new Modules\Attractions\Frontend\Attractions();
