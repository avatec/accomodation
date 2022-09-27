<?php
require_once(__DIR__ . '/sections.class.php');
require_once(__DIR__ . '/content.class.php');

global $sections, $content;

$sections = new Sections();
$content = new Content();
