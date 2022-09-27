<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:02:42
  from '/var/www/html/templates/website/schema/panels/partners.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_633348d2b19398_45442184',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2c46a63a369a055cac193a39dcae0c6359905f71' => 
    array (
      0 => '/var/www/html/templates/website/schema/panels/partners.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_633348d2b19398_45442184 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['partners']->value)) {?><section id="partners">
	<div class="container">
		<h2 class="title"><?php echo Language::get("cms","partner_header");?>
</h2>
		<div id="slider-partners" class="carousel slide">
			<div class="carousel-inner"> 
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['partners']->value, 'i1', false, 'k1', 'lp', array (
  'index' => true,
));
$_smarty_tpl->tpl_vars['i1']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['k1']->value => $_smarty_tpl->tpl_vars['i1']->value) {
$_smarty_tpl->tpl_vars['i1']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_lp']->value['index']++;
?>
			<div class="item<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_lp']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_lp']->value['index'] : null) == 0) {?> active<?php }?>">
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['i1']->value, 'i2');
$_smarty_tpl->tpl_vars['i2']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['i2']->value) {
$_smarty_tpl->tpl_vars['i2']->do_else = false;
?>
				<div class="col-xs-12 col-sm-6 col-md-3">
					<?php if (!empty($_smarty_tpl->tpl_vars['i2']->value['link'])) {?><a href="<?php echo $_smarty_tpl->tpl_vars['i2']->value['link'];?>
" target="_blank"><?php }?>
					<img class="img-responsive" src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
userfiles/partner/<?php echo $_smarty_tpl->tpl_vars['i2']->value['photo'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['i2']->value['name'];?>
" />
					<?php if (!empty($_smarty_tpl->tpl_vars['i2']->value['link'])) {?></a><?php }?>
				</div>
				<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</div>
			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</div>
		</div>
	</div>
</section><?php }
}
}
