<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:02:42
  from '/var/www/html/templates/website/schema/panels/bottom.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_633348d2b81776_24548767',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e8f4d5b6049fefbffe33615f97aa69e3aea58ea1' => 
    array (
      0 => '/var/www/html/templates/website/schema/panels/bottom.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_633348d2b81776_24548767 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/vendor/smarty/smarty/libs/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<footer id="footer">
	<div class="container">
		<div class="col-xs-12 col-sm-4 col-md-3">
			<h5 class="title"><?php echo Language::get("cms","bottom_header_menu");?>
</h5>
			<?php echo Content::generate(array("section"=>2,"class"=>"menu-list","social"=>false));?>

		</div>

		<div class="col-xs-12 col-sm-8 col-md-5">
			<h5 class="title"><?php echo Language::get("cms","bottom_header_about");?>
</h5>
			<?php echo Text::getByName("bottom-about");?>

		</div>

		<div class="col-xs-12 col-sm-12 col-md-4 text-right">
			<h5 class="title"><?php echo Language::get("cms","bottom_header_social");?>
</h5>
			<?php if (!empty($_smarty_tpl->tpl_vars['config']->value['social_facebook'])) {?><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['social_facebook'];?>
" target="_blank" class="social-icon"><span class="fa fa-facebook"></span></a><?php }?>
			<?php if (!empty($_smarty_tpl->tpl_vars['config']->value['social_twitter'])) {?><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['social_twitter'];?>
" target="_blank" class="social-icon"><span class="fa fa-twitter"></span></a><?php }?>
			<?php if (!empty($_smarty_tpl->tpl_vars['config']->value['social_google_plus'])) {?><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['social_google_plus'];?>
" target="_blank" class="social-icon"><span class="fa fa-google-plus"></span></a><?php }?>
			<?php if (!empty($_smarty_tpl->tpl_vars['config']->value['social_instagram'])) {?><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['social_instagram'];?>
" target="_blank" class="social-icon"><span class="fa fa-instagram"></span></a><?php }?>
			<?php if (!empty($_smarty_tpl->tpl_vars['config']->value['social_pinterest'])) {?><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['social_pinterest'];?>
" target="_blank" class="social-icon"><span class="fa fa-pinterest"></span></a><?php }?>

			<p>
				Copyright &copy; <?php echo smarty_modifier_date_format(time(),"%Y");?>
 <?php echo $_smarty_tpl->tpl_vars['config']->value['service_name'];?>
. <?php echo Language::get("cms","bottom_text_copyright");?>
<br/>
				<?php echo Language::get("cms","bottom_text_made_by");?>
: <a href="http:/www.avatec.pl/skrypt-php-baza-noclegowa-avatec-accomodation?r=<?php echo $_smarty_tpl->tpl_vars['app_request_url']->value;?>
" title="Tworzenie stron www, skrypt php bazy noclegowej">www.avatec.pl</a>
			</p>

			<a id="btn-up" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
#topofpage" class="btn btn-primary btn-up"><span class="fa fa-chevron-up"></span></a>
		</div>
	</div>
</footer>
<?php }
}
