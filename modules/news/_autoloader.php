<?php
include __DIR__ . "/category.class.php";
include __DIR__ . "/gallery.class.php";
include __DIR__ . "/news.class.php";

global $news, $NewsCategory, $news_gallery;

$NewsCategory = new NewsCategory();
$news_gallery = new NewsGallery();
$news = new News();
