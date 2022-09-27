<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:02:42
  from '/var/www/html/templates/website/body.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_633348d276b1b1_28461686',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1c204dbc3b2ca2f0ed9e33c56afc96c3c9bb7d30' => 
    array (
      0 => '/var/www/html/templates/website/body.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_633348d276b1b1_28461686 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['config']->value['service_blocked'] == "TRUE") {?>
	<style type="text/css">
		body { background-color: #efefef; height: 100%; position: fixed;}
		#main { width:40%;background:#fff;padding:10px;box-shadow:0px 0px 15px #ccc;margin:10% auto 0 auto; }
		h1 { font-size:18pt;border-bottom: 1px solid #ccc;color:#a10000;font-weight:lighter;padding-bottom:10px;margin-bottom:35px; }
		p.text { text-align: center;font-size:12pt; }
		p.foot { text-align: center;font-size:10pt;margin-top:20px; }
	</style>

	<div id="main">
		<h1 align="center">Serwis tymczasowo zablokowany</h1>
		<p class="text"><?php echo $_smarty_tpl->tpl_vars['config']->value['service_blocked_text'];?>
</p>
		<p class="foot">Powered by <a href="http://www.avatec.pl/">AVATEC FRAMEWORK</a> v7.5</p>
	</div>
<?php } else { ?>
	<?php if (!empty($_smarty_tpl->tpl_vars['tpl']->value['schema'])) {?>
		<?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['app_path']->value)."templates/website/schema/".((string)$_smarty_tpl->tpl_vars['tpl']->value['schema']).".smarty", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
	<?php } else { ?>
		<style type="text/css">
			body { background-color: #efefef; height: 100%; position: fixed;}
			#main { width:40%;background:#fff;padding:10px;box-shadow:0px 0px 15px #ccc;margin:10% auto 0 auto; }
			h1 { font-size:18pt;border-bottom: 1px solid #ccc;color:#a10000;font-weight:lighter;padding-bottom:10px;margin-bottom:35px; }
			p.text { text-align: center;font-size:12pt; }
			p.foot { text-align: center;font-size:10pt;margin-top:20px; }
		</style>
		<div id="main">
			<h1 align="center">NOTICE: No schema selected</h1>
			<p class="text">Something is wrong!<br/>Did You define <b>schema</b> for this url ?<br/>Please check include/content.php or module website.php file</p>
			<p class="foot">Powered by <a href="http://www.avatec.pl/">AVATEC FRAMEWORK</a> v7.5</p>
		</div>
	<?php }?>

<?php }
}
}
