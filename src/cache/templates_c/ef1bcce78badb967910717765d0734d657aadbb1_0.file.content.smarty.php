<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:08:52
  from '/var/www/html/templates/website/schema/content.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_63334a44ba4ff5_90682577',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ef1bcce78badb967910717765d0734d657aadbb1' => 
    array (
      0 => '/var/www/html/templates/website/schema/content.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_63334a44ba4ff5_90682577 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['app_path']->value)."templates/website/schema/panels/top.smarty", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
<div class="container" id="content">
	<div class="row">
		<div class="col-lg-8 content-left">
			<?php if ((isset($_smarty_tpl->tpl_vars['tpl']->value['module']))) {?>
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
		<div class="col-lg-4 content-right">
			<p class="text-center">
				<div class="a-block">
					<?php echo Advertising::show('PAGE');?>

				</div>
			</p>
		</div>
	</div>
</div>
<?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['app_path']->value)."templates/website/schema/panels/bottom.smarty", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
}
}
