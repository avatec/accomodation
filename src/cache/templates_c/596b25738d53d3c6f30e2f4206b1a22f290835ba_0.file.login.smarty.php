<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:09:07
  from '/var/www/html/templates/admin/login.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_63334a53a77895_35957874',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '596b25738d53d3c6f30e2f4206b1a22f290835ba' => 
    array (
      0 => '/var/www/html/templates/admin/login.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_63334a53a77895_35957874 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/vendor/smarty/smarty/libs/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<!DOCTYPE html><html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta charset="utf-8"><title>Dostęp wymaga zalogowania - Avatec Accomodation</title><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="robots" content="noindex,nofollow"><meta name="author" content="Avatec.pl"><link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/admin/css/bootstrap.min.css" rel="stylesheet"><link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/admin/css/animate.css" rel="stylesheet"><!--<link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/admin/css/main.css" rel="stylesheet">//--><link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/admin/css/login.css" rel="stylesheet"><link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/admin/css/font-awesome.min.css"><?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/admin/js/respond.min.js"><?php echo '</script'; ?>
><!--[if lt IE 9]><?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/admin/js/html5shiv.js"><?php echo '</script'; ?>
><![endif]--><!-- Favicon --><link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/admin/img/favicon/favicon.png"></head><body><div class="container-fluid"><div class="row"><div class="col-md-push-4 col-md-4 col-sm-push-3 col-sm-6 col-sx-12"><div class="login-container"><div class="login-wrapper animated fadeInDown"><div id="login" class="show"><div class="login-header"><h4>ACCOMODATION - Strefa wymaga logowania</h4></div><?php if (!empty($_smarty_tpl->tpl_vars['messages']->value['error'])) {?><div class="alert alert-warning"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['messages']->value['error'], 'item');
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
echo $_smarty_tpl->tpl_vars['item']->value;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></div><?php }
echo Form::open("post","/admin/login.html");?>
<div class="form-group has-feedback"><label class="control-label" for="userName">Login</label><?php echo Form::input('text','login');?>
<i class="fa fa-user text-info form-control-feedback"></i></div><div class="form-group has-feedback"><label class="control-label" for="passWord">Hasło</label><?php echo Form::input('password','password');?>
<i class="fa fa-key text-danger form-control-feedback"></i></div><div class="row"><input type="submit" value="Zaloguj mnie" class="btn btn-danger btn-lg btn-block"></div><?php echo Form::close();?>
<p><small>Copyright (c) 2010-<?php echo smarty_modifier_date_format(time(),"%Y");?>
 Avatec.pl. Wszystkie prawa zastrzeżone</small></p></div></div></div></div></div></div><?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/admin/js/jquery.js"><?php echo '</script'; ?>
><?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/admin/js/bootstrap.min.js"><?php echo '</script'; ?>
></body></html>
<?php }
}
