<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:02:42
  from '/var/www/html/templates/website/schema/panels/top/_menu.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_633348d285db23_83748747',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f8ae19884b2fe8dce1c8c0ef84d1329d3feee499' => 
    array (
      0 => '/var/www/html/templates/website/schema/panels/top/_menu.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_633348d285db23_83748747 (Smarty_Internal_Template $_smarty_tpl) {
?><section id="main-menu">
	<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/">
						<img class="logo img-responsive" src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/images/<?php if (!empty($_smarty_tpl->tpl_vars['config']->value['website_logo'])) {
echo $_smarty_tpl->tpl_vars['config']->value['website_logo'];
} else { ?>logo.png<?php }?>" alt="<?php echo $_smarty_tpl->tpl_vars['config']->value['service_name'];?>
" />
					</a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li class="mobile-menu">
						<?php if (User::$user['id'] > 0) {?>
							<?php if (User::$user['user_type'] == "OWNER") {?>
							<a class="link" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
panel/objects/"><?php echo Language::get("system","user_btn_offers");?>
</a>
							<?php }?>
							<a class="link" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
panel/account"><?php echo Language::get("system","user_btn_account");?>
</a>
							<a class="link" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
panel/change-password"><?php echo Language::get("system","user_btn_change_password");?>
</a>
							<a class="link" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
panel/logout"><span class="fa fa-sign-out"></span></a>
						<?php } else { ?>
							<a class="link" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
panel/login"><span class="fa fa-sign-in"></span></a>
							<a class="link" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
panel/register"><?php echo Language::get("system","user_btn_register");?>
</a>
							<a class="link" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
panel/password-reset"><?php echo Language::get("system","user_btn_password_forget");?>
</a>
						<?php }?>
						</li>
						<?php echo Content::generate(array("section"=>1,"class"=>"nav navbar-nav","dropdown"=>true));?>

						<li class="mobile-menu-lang">
							<a class="link-inline<?php if (Language::$selected == "pl") {?> selected<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
language-pl/?redirect=<?php echo $_smarty_tpl->tpl_vars['app_request_url']->value;?>
"><em class="flag-icon flag-icon-pl"></em></a>
				<a class="link-inline<?php if (Language::$selected == "en") {?> selected<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
language-en/?redirect=<?php echo $_smarty_tpl->tpl_vars['app_request_url']->value;?>
"><em class="flag-icon flag-icon-gb"></em></a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
</section><?php }
}
