<?php
require_once __DIR__ . '/translations.class.php';
global $translations;
$translations = new Translations();

require_once __DIR__ . '/text.class.php';
global $text;
$text = new Text();

require_once __DIR__ . '/emails.class.php';
global $emails;
$emails = new Emails();

if(!isset($config['basic'])) {
    require_once __DIR__ . '/sms.class.php';
    global $sms;
    $sms = new SMS();
}

require_once __DIR__ . '/promotion.class.php';
global $promotion;
$promotion = new Promotion();

require_once __DIR__ . '/stats.class.php';
global $stats;
$stats = new Stats();

require_once __DIR__ . '/system.class.php';
global $system;
$system = new System();

require_once __DIR__ . '/users.class.php';
global $user;
$user = new User();
