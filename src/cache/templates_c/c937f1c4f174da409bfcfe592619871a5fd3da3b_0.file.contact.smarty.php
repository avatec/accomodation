<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:08:54
  from '/var/www/html/templates/website/schema/contact.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_63334a466619b7_31261630',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c937f1c4f174da409bfcfe592619871a5fd3da3b' => 
    array (
      0 => '/var/www/html/templates/website/schema/contact.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_63334a466619b7_31261630 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/vendor/smarty/smarty/libs/plugins/function.mailto.php','function'=>'smarty_function_mailto',),));
$_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['app_path']->value)."templates/website/schema/panels/top.smarty", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
if (((isset($_smarty_tpl->tpl_vars['messages']->value['notice']))) || ((isset($_smarty_tpl->tpl_vars['messages']->value['error']))) || ((isset($_smarty_tpl->tpl_vars['messages']->value['warning']))) || ((isset($_smarty_tpl->tpl_vars['messages']->value['info'])))) {?>
<div id="panel-status-bar" class="panel-status-bar <?php if ((isset($_smarty_tpl->tpl_vars['messages']->value['notice']))) {?>success<?php }
if ((isset($_smarty_tpl->tpl_vars['messages']->value['error']))) {?>error<?php }
if ((isset($_smarty_tpl->tpl_vars['messages']->value['warning']))) {?>warning<?php }
if ((isset($_smarty_tpl->tpl_vars['messages']->value['info']))) {?>info<?php }?>">
	<div class="container">
		<div class="col-md-1 panel-icon">
			<?php if ((isset($_smarty_tpl->tpl_vars['messages']->value['notice']))) {?><em class="fa fa-check-circle-o fa-2x"></em><?php }?>
			<?php if ((isset($_smarty_tpl->tpl_vars['messages']->value['error']))) {?><em class="fa fa-exclamation-circle fa-2x"></em><?php }?>
			<?php if ((isset($_smarty_tpl->tpl_vars['messages']->value['warning']))) {?><em class="fa fa-exclamation-triangle fa-2x"></em><?php }?>
			<?php if ((isset($_smarty_tpl->tpl_vars['messages']->value['info']))) {?><em class="fa fa-info-circle fa-2x"></em><?php }?>
		</div>
		<div class="col-md-11">
			<?php if ((isset($_smarty_tpl->tpl_vars['messages']->value['notice']))) {
echo $_smarty_tpl->tpl_vars['messages']->value['notice'];
}?>
			<?php if ((isset($_smarty_tpl->tpl_vars['messages']->value['error']))) {
echo $_smarty_tpl->tpl_vars['messages']->value['error'];
}?>
			<?php if ((isset($_smarty_tpl->tpl_vars['messages']->value['warning']))) {
echo $_smarty_tpl->tpl_vars['messages']->value['warning'];
}?>
			<?php if ((isset($_smarty_tpl->tpl_vars['messages']->value['info']))) {
echo $_smarty_tpl->tpl_vars['messages']->value['info'];
}?>
		</div>
	</div>
	<a class="panel-close"><span class="fa fa-times"></span></a>
</div><?php }?>
<div class="container" id="content">
	<div class="row">
		<div class="col-lg-8 content-left">
			<h2><?php echo Language::get("cms","contact_form");?>
</h2>
			
			<?php echo Form::open("POST");?>

			<div class="form-group">
				<label for="inputName" class="col-sm-4 control-label"><?php echo Language::get("cms","contact_first_last_name");?>
: <sup>*</sup></label>
				<div class="col-sm-8">
					<?php echo Form::input2(array("type"=>"text","name"=>"name","placeholder"=>Language::get("cms","contact_name_placeholder"),"required"=>true));?>

				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail" class="col-sm-4 control-label"><?php echo Language::get("cms","contact_email_address");?>
: <sup>*</sup></label>
				<div class="col-sm-8">
					<?php echo Form::input2(array("type"=>"email","name"=>"email","placeholder"=>Language::get("cms","contact_email_placeholder"),"required"=>true));?>

				</div>
			</div>
			<div class="form-group">
				<label for="inputPhone" class="col-sm-4 control-label"><?php echo Language::get("cms","contact_phone_number");?>
:</label>
				<div class="col-sm-8">
					<?php echo Form::input2(array("type"=>"text","name"=>"phone","placeholder"=>Language::get("cms","contact_phone_placeholder")));?>

				</div>
			</div>
			<div class="form-group">
				<label for="inputPhone" class="col-sm-4 control-label"><?php echo Language::get("cms","contact_message");?>
: <sup>*</sup></label>
				<div class="col-sm-8">
					<?php echo Form::text2(array("name"=>"text","id"=>"text","placeholder"=>Language::get("cms","contact_msg_placeholder"),"required"=>true));?>

				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-4 col-sm-8">
				<label><small><?php echo Form::checkbox("rules[1]","TRUE",null,true);?>
 <?php echo $_smarty_tpl->tpl_vars['config']->value['rules_rodo_1'];?>
<sup>*</sup></small></label>
				<label><small><?php echo Form::checkbox("rules[2]","TRUE",null,true);?>
 <?php echo $_smarty_tpl->tpl_vars['config']->value['rules_rodo_2'];?>
</small></label>
				<label><small><?php echo Form::checkbox("rules[3]","TRUE");?>
 <?php echo $_smarty_tpl->tpl_vars['config']->value['rules_rodo_3'];?>
</small></label>
				</div>
			</div>
			
			<?php if ((isset($_smarty_tpl->tpl_vars['config']->value['google_recaptcha_sitekey'])) && (isset($_smarty_tpl->tpl_vars['config']->value['google_recaptcha_secretkey']))) {?>
			<div class="form-group"><div class="col-sm-offset-4 col-sm-8">
				<br/>
				<?php echo '<script'; ?>
 src='https://www.google.com/recaptcha/api.js'><?php echo '</script'; ?>
>
				<div class="g-recaptcha" data-sitekey="<?php echo $_smarty_tpl->tpl_vars['config']->value['google_recaptcha_sitekey'];?>
"></div>
			</div></div>
			<?php }?>
					
			<div class="form-group">
				<div class="col-sm-offset-4 col-sm-8">
					<button type="submit" role="button" class="btn btn-primary btn-block btn-lg"><span class="fa fa-envelope"></span> <?php echo Language::get("cms","contact_btn_send");?>
</button>
				</div>
			</div>
			<?php echo Form::close();?>

		</div>
		<div class="col-lg-4 content-right">
			<h2><?php echo Language::get("cms","contact_company_name");?>
</h2>
			<p>
				<?php if ((isset($_smarty_tpl->tpl_vars['config']->value['service_name']))) {?><b><?php echo $_smarty_tpl->tpl_vars['config']->value['service_name'];?>
</b><br><?php }?>
				<?php if ((isset($_smarty_tpl->tpl_vars['config']->value['service_address_2']))) {
echo $_smarty_tpl->tpl_vars['config']->value['service_address_2'];?>
<br><?php }?>
				<?php if ((isset($_smarty_tpl->tpl_vars['config']->value['service_postcode_2'])) && (isset($_smarty_tpl->tpl_vars['config']->value['service_city_2']))) {
echo $_smarty_tpl->tpl_vars['config']->value['service_postcode_2'];?>
 <?php echo $_smarty_tpl->tpl_vars['config']->value['service_city_2'];?>
<br><br><?php }?>

				<?php if (!empty($_smarty_tpl->tpl_vars['config']->value['service_phone_1'])) {
echo Language::get("cms","contact_phone");?>
: <?php echo $_smarty_tpl->tpl_vars['config']->value['service_phone_1'];?>
<br><?php }?>
				<?php if (!empty($_smarty_tpl->tpl_vars['config']->value['service_phone_2'])) {
echo Language::get("cms","contact_phone");?>
: <?php echo $_smarty_tpl->tpl_vars['config']->value['service_phone_2'];?>
<br><?php }?>
				<?php if (!empty($_smarty_tpl->tpl_vars['config']->value['contact_email'])) {
echo Language::get("cms","contact_email");?>
: <?php echo smarty_function_mailto(array('address'=>((string)$_smarty_tpl->tpl_vars['config']->value['default_email']),'encode'=>"hex"),$_smarty_tpl);?>
<br><?php }?>
				<br>
				<?php if (!empty($_smarty_tpl->tpl_vars['config']->value['bank_name']) && !empty($_smarty_tpl->tpl_vars['config']->value['bank_account'])) {?><b><?php echo Language::get("cms","contact_bank_account");?>
</b>:<br>
				<?php echo $_smarty_tpl->tpl_vars['config']->value['bank_name'];?>
<br>
				<?php echo $_smarty_tpl->tpl_vars['config']->value['bank_account'];?>

				<?php }?>
			</p>
		</div>
	</div>
</div>
<?php if ($_smarty_tpl->tpl_vars['config']->value['show_partners_main'] == "TRUE") {
$_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['app_path']->value)."templates/website/schema/panels/partners.smarty", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
}
$_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['app_path']->value)."templates/website/schema/panels/bottom.smarty", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
}
}
