<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:02:42
  from '/var/www/html/templates/website/schema/panels/slider.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_633348d28d0f79_32136519',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '12bf54b4848c090e21cde7f1a5f11a6e1a303176' => 
    array (
      0 => '/var/www/html/templates/website/schema/panels/slider.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_633348d28d0f79_32136519 (Smarty_Internal_Template $_smarty_tpl) {
?><section id="slider" style="background: url('<?php echo Slider::getForNow();?>
');">
	<div class="container">
		
		<div class="search-block">
			<form action="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
search" method="get" class="form-horizontal">
			<?php echo Form::hidden("quicksearch","true");?>

				<div class="col-xs-12 col-sm-12 col-md-10">
					<div class="input-group">
						<input type="text" name="q" class="form-control input-lg" placeholder="<?php echo Language::get("cms","search_name");?>
" />
						<div class="input-group-addon">
							<select name="t" class="form-control">
								<option value="name"><?php echo Language::get("cms","search_byname");?>
</option>
								<option value="city"><?php echo Language::get("cms","search_city");?>
</option>
								<option value="state"><?php echo Language::get("cms","search_state");?>
</option>
								<option value="type"><?php echo Language::get("cms","search_type");?>
</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-2">
					<button type="submit" class="btn btn-primary btn-block btn-lg">
						<span class="fa fa-search"></span>
					</button>
				</div>
			</form>
		</div>
	</div>
</section><?php }
}
