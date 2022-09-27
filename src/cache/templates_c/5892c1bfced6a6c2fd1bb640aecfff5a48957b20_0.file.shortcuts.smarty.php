<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:02:42
  from '/var/www/html/templates/website/schema/panels/shortcuts.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_633348d2a13475_59721720',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5892c1bfced6a6c2fd1bb640aecfff5a48957b20' => 
    array (
      0 => '/var/www/html/templates/website/schema/panels/shortcuts.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_633348d2a13475_59721720 (Smarty_Internal_Template $_smarty_tpl) {
?><section id="shortcuts">
	<div class="container">
		<h2 class="title text-center"><?php echo Language::get("cms","shortcuts_name");?>
</h2>
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['shortcuts']->value, 'item');
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
		<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
			<a class="item thumbnail" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
search/<?php echo $_smarty_tpl->tpl_vars['item']->value['rewrite'];?>
/">
				<img class="img-responsive img-rounded" src="/userfiles/locations/<?php echo $_smarty_tpl->tpl_vars['item']->value['icon'];?>
" alt="" />
				<div class="shortcut-title">
					<h4 class="text-center"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</h4>
				</div>
			</a>
		</div>
		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	</div>
</section><?php }
}
