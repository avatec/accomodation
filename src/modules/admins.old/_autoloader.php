<?php
use Modules\Admins\Backend\Admins;
use Modules\Admins\Backend\Tokens as AdminsTokens;

require_once __DIR__ . '/admins.class.php';
require_once __DIR__ . '/tokens.class.php';

global $Admins, $Admins_token;
$Admins = new Admins();
$Admins_token = new AdminsTokens();