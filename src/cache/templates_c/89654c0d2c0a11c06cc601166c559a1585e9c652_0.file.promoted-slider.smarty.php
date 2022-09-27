<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:02:42
  from '/var/www/html/templates/website/schema/objects/promoted-slider.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_633348d2961fe6_85307744',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '89654c0d2c0a11c06cc601166c559a1585e9c652' => 
    array (
      0 => '/var/www/html/templates/website/schema/objects/promoted-slider.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_633348d2961fe6_85307744 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/vendor/smarty/smarty/libs/plugins/modifier.truncate.php','function'=>'smarty_modifier_truncate',),));
if ((isset($_smarty_tpl->tpl_vars['promoted']->value))) {?>
<section id="promotion-horizontal" data-type="<?php echo $_smarty_tpl->tpl_vars['config']->value['promoted_main_type'];?>
">
	<div class="container">
		<h2 class="title text-center"><?php echo Language::get("cms","promoted_offers");?>
</h2>
		<hr class="half-line" />

		<div class="promotion-items gallery gallery-promotion-items" data-navigation="promotion-items-nav">
			<div id="promotion-items-nav" class="crsl-nav">
				<a href="#" class="previous"><i class="fa fa-chevron-left"></i></a>
				<a href="#" class="next"><i class="fa fa-chevron-right"></i></a>
			</div>
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
	</div>
</section><?php }
}
}
