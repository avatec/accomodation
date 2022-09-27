<?php
use Modules\Admins as Admins;
use Modules\Admins\Tokens as AdminsTokens;

require_once __DIR__ . '/admins.class.php';
require_once __DIR__ . '/tokens.class.php';

global $admins, $admins_token;
$admins = new Admins();
$admins_token = new AdminsTokens();