<?php
use Modules\Attractions\Backend\Attractions;
use Modules\Attractions\Backend\Category;
use Modules\Attractions\Backend\Photos;

/**
 *  Backend module autoloader
 */

require_once __DIR__ . '/attractions.php';
require_once __DIR__ . '/category.php';
require_once __DIR__ . '/photos.php';

global $attractions;
$attractions = new Modules\Attractions\Backend\Attractions();

global $att_category;
$att_category = new Modules\Attractions\Backend\Category();

global $att_photos;
$att_photos = new Modules\Attractions\Backend\Photos();
