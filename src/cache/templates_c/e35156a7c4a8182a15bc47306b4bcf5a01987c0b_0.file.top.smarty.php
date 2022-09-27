<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:05:39
  from '/var/www/html/templates/website/schema/panels/top.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_633349836148a4_95560221',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e35156a7c4a8182a15bc47306b4bcf5a01987c0b' => 
    array (
      0 => '/var/www/html/templates/website/schema/panels/top.smarty',
      1 => 1664305538,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_633349836148a4_95560221 (Smarty_Internal_Template $_smarty_tpl) {
?><header>
	<div id="topofpage"></div>
	<section id="user-menu">
		<div class="container">
			<div class="pull-left">
				<?php if (!empty(User::$user['id'])) {?>
				<span class="badge"><?php echo Language::get("system","user_text_login_as");?>
: <u><?php echo User::$user['login'];?>
</u></span>
				<?php if (User::$user['user_type'] == "OWNER") {?>
				<a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
panel/objects/"><?php echo Language::get("system","user_btn_offers");?>
</a>
				<?php if (!empty($_smarty_tpl->tpl_vars['config']->value['exclusive'])) {?>
				<a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
panel/booking/"><?php echo Language::get("booking","btn_booking_list");?>
 <span class="label label-default"><?php echo $_smarty_tpl->tpl_vars['stats']->value['booking']['new'];?>
</span></a>
				<a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
panel/settlement/"><?php echo Language::get("booking","user_btn_settlement");?>
</a>
				<?php }?>
				<?php }?>
				<a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
panel/account"><?php echo Language::get("system","user_btn_account");?>
</a>
				<a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
panel/change-password"><?php echo Language::get("system","user_btn_change_password");?>
</a>
				<a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
panel/logout"><?php echo Language::get("system","user_btn_logout");?>
</a>
				
				<?php } else { ?>
				<a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
panel/login"><?php echo Language::get("system","user_btn_login");?>
</a>
				<a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
panel/register"><?php echo Language::get("system","user_btn_register");?>
</a>
				<a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
panel/password-reset"><?php echo Language::get("system","user_btn_password_forget");?>
</a>
				<?php }?>
			</div>
			<div class="languages pull-right">
				<a class="lang-item<?php if (Language::$selected == "pl") {?> selected<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
language-pl/?redirect=<?php echo $_smarty_tpl->tpl_vars['app_request_url']->value;?>
"><em class="flag-icon flag-icon-pl"></em></a>
				<a class="lang-item<?php if (Language::$selected == "en") {?> selected<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
language-en/?redirect=<?php echo $_smarty_tpl->tpl_vars['app_request_url']->value;?>
"><em class="flag-icon flag-icon-gb"></em></a>
			</div>
			<div class="clearfix"></div>
		</div>
	</section>
	<?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['app_path']->value)."templates/website/schema/panels/top/_menu.smarty", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
</header><?php }
}
