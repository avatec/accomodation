<?php

use Modules\Admins\Backend\Admins as Admins;
use Modules\Admins\Backend\Tokens as AdminsTokens;

require_once __DIR__ . '/admins.php';
require_once __DIR__ . '/tokens.php';

global $Admins, $AdminsTokens;
if (!class_exists('Admins')) {
    $Admins = new Admins();
}
if (!class_exists('AdminsTokens')) {
    $AdminsTokens = new AdminsTokens();
}
