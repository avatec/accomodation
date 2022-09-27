<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:08:51
  from '/var/www/html/templates/website/schema/objects/promoted.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_63334a43090487_58229258',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3f6d1a43b64cec506d22c7acbe8aff5361c68bca' => 
    array (
      0 => '/var/www/html/templates/website/schema/objects/promoted.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_63334a43090487_58229258 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/vendor/smarty/smarty/libs/plugins/modifier.truncate.php','function'=>'smarty_modifier_truncate',),));
if ((isset($_smarty_tpl->tpl_vars['promoted']->value))) {?><section id="promotion-horizontal">
	<div class="container">
		<h2 class="title text-center"><?php echo Language::get("cms","promoted_offers");?>
</h2>
		<hr class="half-line" />

		<div class="promotion-items">
			<div class="crsl-wrap">
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['promoted']->value, 'item', false, NULL, 'lp', array (
));
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
			<figure class="crsl-item">
				<div class="img-figure">
					<div class="cat"><?php echo ObjectsTypes::getName($_smarty_tpl->tpl_vars['item']->value['type']);?>
</div>
					<a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
noclegi/<?php echo Kernel::rewrite($_smarty_tpl->tpl_vars['item']->value['city']);?>
/<?php echo Kernel::rewrite($_smarty_tpl->tpl_vars['item']->value['name']);?>
-i<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
">
						<img alt="<?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
" src="<?php echo ObjectsPhotos::getImage($_smarty_tpl->tpl_vars['item']->value['id']);?>
">
					</a>

					<div class="title">
						<h1><a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
noclegi/<?php echo Kernel::rewrite($_smarty_tpl->tpl_vars['item']->value['city']);?>
/<?php echo Kernel::rewrite($_smarty_tpl->tpl_vars['item']->value['name']);?>
-i<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</a></h1>
						<a class="city-link" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
noclegi/<?php echo Kernel::rewrite($_smarty_tpl->tpl_vars['item']->value['city']);?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['city'];?>
</a>
					</div>
					<div class="description">
						<?php echo smarty_modifier_truncate(preg_replace('!<[^>]*?>!', ' ', html_entity_decode(html_entity_decode($_smarty_tpl->tpl_vars['item']->value['short_description']))),80);?>

					</div>
				</div>
			</figure>
			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		</div>
	</div>
</section><?php }
}
}
