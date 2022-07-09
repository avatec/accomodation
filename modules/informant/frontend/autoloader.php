<?php
use Modules\Informant\Frontend\Informant;

/**
 *  Frontend module autoloader
 */

require_once __DIR__ . '/informant.php';

global $informant;
$informant = new Modules\Informant\Frontend\Informant();
