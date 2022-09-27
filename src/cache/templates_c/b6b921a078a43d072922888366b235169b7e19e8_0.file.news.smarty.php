<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:08:53
  from '/var/www/html/templates/website/schema/news.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_63334a459a9606_73516338',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b6b921a078a43d072922888366b235169b7e19e8' => 
    array (
      0 => '/var/www/html/templates/website/schema/news.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_63334a459a9606_73516338 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['app_path']->value)."templates/website/schema/panels/top.smarty", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
<div class="container" id="content">
	<div class="row">
		<div class="col-xs-12 col-lg-3 content-right">
			<h3 class="title"><?php echo Language::get("cms","news_category_header");?>
</h3>
			<ul class="nav nav-stacked">
				<li class="active"><a href="/news">Najnowsze</a></li>
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['category']->value, 'item');
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
				<li><a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
 <b>(<?php echo $_smarty_tpl->tpl_vars['item']->value['num'];?>
)</b></a></li>
				<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</ul>
			
			
			<p class="text-center">
				<div class="a-block">
					<?php echo Advertising::show('PAGE');?>

				</div>
			</p>
		</div>
		<div class="col-xs-12 col-lg-9 content-left">
			<?php if (!empty($_smarty_tpl->tpl_vars['tpl']->value['module'])) {?>
			<?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['app_path']->value)."modules/".((string)$_smarty_tpl->tpl_vars['tpl']->value['module'])."/templates/website/".((string)$_smarty_tpl->tpl_vars['tpl']->value['file']), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
			<?php } else { ?>
			<h1 class="title"><?php echo $_smarty_tpl->tpl_vars['content']->value['name'];?>
</h1>
			<div class="content-text">
			<?php echo $_smarty_tpl->tpl_vars['content']->value['text'];?>

			</div>
			<?php }?>
		</div>
	</div>
</div>
<?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['app_path']->value)."templates/website/schema/panels/bottom.smarty", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
}
}
