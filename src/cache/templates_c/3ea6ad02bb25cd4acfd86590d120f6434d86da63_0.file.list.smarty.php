<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:08:53
  from '/var/www/html/modules/news/templates/website/list.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_63334a459f8e63_24164861',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3ea6ad02bb25cd4acfd86590d120f6434d86da63' => 
    array (
      0 => '/var/www/html/modules/news/templates/website/list.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_63334a459f8e63_24164861 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['news']->value['list'])) {?>
<div class="panel-news">
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['news']->value['list'], 'item', false, NULL, 'lp', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_lp']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_lp']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_lp']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_lp']->value['total'];
?>
	<div class="box-news">
		<?php if (!empty($_smarty_tpl->tpl_vars['item']->value['icon'])) {?><div class="box-header">
			<a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['link'];?>
"><img src="<?php echo News::$UploadUrl;?>
icon/<?php echo $_smarty_tpl->tpl_vars['item']->value['icon'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['item']->value['topic'];?>
 photo" class="img-responsive" /></a>
			<h2 class="title"><?php echo $_smarty_tpl->tpl_vars['item']->value['topic'];?>
</h2>
		</div><?php }?>
		<div class="box-body">
			<?php if (empty($_smarty_tpl->tpl_vars['item']->value['icon'])) {?><h2 class="title"><?php echo $_smarty_tpl->tpl_vars['item']->value['topic'];?>
</h2><?php }?>
			<p>
				<span class="label label-info"><i class="fa fa-calendar"></i>&nbsp;<?php echo Common::dateAsText($_smarty_tpl->tpl_vars['item']->value['create_date']);?>
</span>
				<span class="label label-warning"><i class="fa fa-tag"></i><?php echo NewsCategory::getNameById($_smarty_tpl->tpl_vars['item']->value['category']);?>
</span>
			</p>
			<?php echo $_smarty_tpl->tpl_vars['item']->value['preface'];?>

		</div>
		<div class="box-footer">
			<div class="row">
				<div class="col-xs-6">
					
				</div>
				<div class="col-xs-6 text-right">
					<a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['link'];?>
" class="btn btn-default btn-sm">
						<?php echo Language::get('news','read_more');?>

					</a>
				</div>
			</div>
		</div>
	</div>
	<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_lp']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_lp']->value['last'] : null) !== true) {?><hr/><?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</div>
<?php }?>

<?php echo Paginate::get();
}
}
