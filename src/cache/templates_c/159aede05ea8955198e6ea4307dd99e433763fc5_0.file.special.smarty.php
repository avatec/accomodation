<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:02:42
  from '/var/www/html/templates/website/schema/objects/special.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_633348d29acbe6_06440239',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '159aede05ea8955198e6ea4307dd99e433763fc5' => 
    array (
      0 => '/var/www/html/templates/website/schema/objects/special.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_633348d29acbe6_06440239 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['special']->value)) {?><section id="special">
	<div class="container">
		<h2 class="title text-center"><?php echo Language::get("cms","special_name");?>
</h2>
		<hr class="half-line" />
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['special']->value, 'item');
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<a class="item thumbnail" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
search/special/<?php echo $_smarty_tpl->tpl_vars['item']->value['rewrite'];?>
/">
				<img class="img-responsive img-rounded" src="/userfiles/special/<?php echo $_smarty_tpl->tpl_vars['item']->value['icon'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
" />
				<h4 class="text-center"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</h4>
				<div class="btn-check-offer">
					<button type="button" class="btn btn-default">sprawdź oferty</button>
				</div>
			</a>
		</div>
		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	</div>
</section><?php }
}
}
