<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:02:42
  from '/var/www/html/templates/website/schema/panels/news.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_633348d2a89414_28432043',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '257cdf16a3aeb07282a3b661df6ecff45055f5fb' => 
    array (
      0 => '/var/www/html/templates/website/schema/panels/news.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_633348d2a89414_28432043 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/vendor/smarty/smarty/libs/plugins/modifier.truncate.php','function'=>'smarty_modifier_truncate',),));
?>
<h3 class="title"><?php echo Language::get("cms","news_header");?>
</h3>
<?php if (!empty($_smarty_tpl->tpl_vars['news']->value)) {?>
<div class="panel-news">
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['news']->value, 'item', false, NULL, 'lp', array (
  'iteration' => true,
));
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_lp']->value['iteration']++;
?>
		<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_lp']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_lp']->value['iteration'] : null) == 1) {?>
		<?php if (!empty($_smarty_tpl->tpl_vars['item']->value['icon'])) {?>
		<div class="box-news">
			<div class="box-header">
				<a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['link'];?>
"><img src="<?php echo News::$UploadUrl;?>
icon/<?php echo $_smarty_tpl->tpl_vars['item']->value['icon'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['item']->value['topic'];?>
 photo" class="img-responsive" /></a>
				<h2 class="title"><?php echo $_smarty_tpl->tpl_vars['item']->value['topic'];?>
<br/><a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['link'];?>
" class="btn btn-link">
					<?php echo Language::get('news','read_more');?>

				</a></h2>
			</div>
		</div>
		<?php } else { ?>
		<div class="media media-list">
			<?php if (!empty($_smarty_tpl->tpl_vars['item']->value['icon'])) {?><div class="media-left">
				<a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
news/view/<?php echo Kernel::rewrite($_smarty_tpl->tpl_vars['item']->value['topic']);?>
-i<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
">
					<img class="media-object img-responsive" src="<?php echo News::$UploadUrl;?>
icon/<?php echo $_smarty_tpl->tpl_vars['item']->value['icon'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['item']->value['topic'];?>
" />
				</a>
			</div><?php }?>
			<div class="media-body">
				<h3><?php echo $_smarty_tpl->tpl_vars['item']->value['topic'];?>
</h3>
				<?php echo smarty_modifier_truncate(html_entity_decode(html_entity_decode($_smarty_tpl->tpl_vars['item']->value['content'])),360);?>

				<p class="text-right">
					<a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
news/view/<?php echo Kernel::rewrite($_smarty_tpl->tpl_vars['item']->value['topic']);?>
-i<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
"><?php echo Language::get("cms","news_more");?>
 &raquo;</a>
				</p>
			</div>
		</div>
		<?php }?>
		<?php } else { ?>
		<div class="media media-list">
			<?php if (!empty($_smarty_tpl->tpl_vars['item']->value['icon'])) {?><div class="media-left">
				<a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
news/view/<?php echo Kernel::rewrite($_smarty_tpl->tpl_vars['item']->value['topic']);?>
-i<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
">
					<img class="media-object img-responsive" src="<?php echo News::$UploadUrl;?>
icon/<?php echo $_smarty_tpl->tpl_vars['item']->value['icon'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['item']->value['topic'];?>
" />
				</a>
			</div><?php }?>
			<div class="media-body">
				<h3><?php echo $_smarty_tpl->tpl_vars['item']->value['topic'];?>
</h3>
				<?php echo smarty_modifier_truncate(html_entity_decode(html_entity_decode($_smarty_tpl->tpl_vars['item']->value['content'])),360);?>

				<p class="text-right">
					<a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
news/view/<?php echo Kernel::rewrite($_smarty_tpl->tpl_vars['item']->value['topic']);?>
-i<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
"><?php echo Language::get("cms","news_more");?>
 &raquo;</a>
				</p>
			</div>
		</div>
		<?php }?>
	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</div>
<?php }?>

<?php echo Paginate::get();
}
}
