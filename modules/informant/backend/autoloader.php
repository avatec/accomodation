<?php
use Modules\Informant\Backend\Informant;
use Modules\Informant\Backend\Category;

/**
 *  Backend module autoloader
 */

require_once __DIR__ . '/informant.php';
require_once __DIR__ . '/category.php';

global $informant;
$informant = new Modules\Informant\Backend\Informant();

global $category;
$category = new Modules\Informant\Backend\Category();
