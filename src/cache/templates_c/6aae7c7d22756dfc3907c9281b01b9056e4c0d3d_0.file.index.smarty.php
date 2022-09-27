<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:05:07
  from '/var/www/html/templates/errors/index.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_63334963ba8481_57483825',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6aae7c7d22756dfc3907c9281b01b9056e4c0d3d' => 
    array (
      0 => '/var/www/html/templates/errors/index.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_63334963ba8481_57483825 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="<?php echo Language::$selected;?>
">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/images/favicon.ico" type="image/x-icon">
	<title>Błąd 404 - plik lub ścieżka nie istnieje</title>
	<meta name="robot" content="noindex,nofollow" />
	<!--<meta http-equiv="refresh" content="5;url=<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
" />//-->
	<link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/css/style.css" rel="stylesheet" media="screen">
	<link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/errors/css/style.css" rel="stylesheet" media="screen">
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	      <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/js/html5shiv.min.js"><?php echo '</script'; ?>
>
	      <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/js/respond.min.js"><?php echo '</script'; ?>
>
    <![endif]-->
    
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/js/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/js/bootstrap.min.js"><?php echo '</script'; ?>
>
</head>
<body>
	<div class="logo">
		<p align="center"><a href="/"><img src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/images/logo.png" class="img-responsive" alt="..."/></a></p>
	</div>
	<div class="container">
		<div class="page-header text-center">
			<h1 class="text-center"><small>404</small><br/>Przepraszamy, ale wybrana strona nie istnieje lub została usunięta</h1>
			<p align="center">Zostaniesz automatycznie przeniesiony na stronę główną serwisu</p>
		</div>
		<p class="text-center">
			<a class="btn btn-primary btn-lg" href="/" role="button">Wróć na stronę główną</a><br/>
		</p>

	</div>
</body>
</html><?php }
}
