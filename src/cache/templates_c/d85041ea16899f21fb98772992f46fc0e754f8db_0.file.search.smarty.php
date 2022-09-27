<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:08:51
  from '/var/www/html/templates/website/schema/objects/search.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_63334a4302bc91_08580504',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd85041ea16899f21fb98772992f46fc0e754f8db' => 
    array (
      0 => '/var/www/html/templates/website/schema/objects/search.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_63334a4302bc91_08580504 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['app_path']->value)."templates/website/schema/panels/top.smarty", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
<div id="search">
	<div class="container">

	<h3 class="title"><?php echo Language::get("cms","search_title");?>
</h3>
			<div class="form-horizontal">
			<div class="form-group">
				<div class="col-xs-12 col-md-4">
					<?php echo Form::input2(array("type"=>"text","name"=>"q","placeholder"=>Language::get("cms","search_name")));?>

				</div>
				<div class="col-xs-12 col-md-3">
					<?php echo Form::input2(array("type"=>"text","name"=>"c","placeholder"=>Language::get("cms","search_city")));?>

				</div>
				<div class="col-xs-12 col-md-3">
					<?php echo Form::select2(array("name"=>"s","values"=>ObjectsStates::getSelect(),"empty"=>1,"empty_name"=>Language::get("cms","search_state_all")));?>

				</div>
				<div class="col-xs-12 col-md-2">
					<?php echo Form::select2(array("name"=>"t","values"=>ObjectsTypes::getSelect(),"empty"=>1,"empty_name"=>Language::get("cms","search_type_all")));?>

				</div>
			</div>
			<div id="advancedSearchLayer">
				<fieldset>
					<div class="form-group">
						<div class="col-md-2">
							<div class="input-group">
								<?php echo Form::input2(array("type"=>"text","name"=>"cf","placeholder"=>Language::get("cms","search_amount_from")));?>

								<div class="input-group-addon">zł</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="input-group">
								<?php echo Form::input2(array("type"=>"text","name"=>"ct","placeholder"=>Language::get("cms","search_amount_to")));?>

								<div class="input-group-addon">zł</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="input-group">
								<?php echo Form::input2(array("type"=>"number","name"=>"rp","placeholder"=>Language::get("cms","search_room_person"),"min"=>1,"max"=>10));?>

								<div class="input-group-addon">osób</div>
							</div>
						</div>

						<div class="col-md-3">
							<?php echo Form::select2(array("name"=>"l","values"=>ObjectsLocation::getSelect(),"empty"=>1,"empty_name"=>Language::get("cms","search_location_all")));?>

						</div>
					</div>
					<div class="form-group">
						<?php echo Form::hidden("photo",0);?>

						<?php echo Form::checkbox3("photo",1,Language::get("cms","search_adv_only_photo"));?>

						<?php if (!(isset($_smarty_tpl->tpl_vars['config']->value['basic']))) {?>
						<?php echo Form::hidden("video",0);?>

						<?php echo Form::checkbox3("video",1,Language::get("cms","search_adv_only_video"));?>

						<?php }?>
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<?php if ((isset($_smarty_tpl->tpl_vars['distance']->value))) {?><h4><?php echo Language::get("objects","objects_distance_text");?>
</h4><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['distance']->value, 'item');
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
							<div class="col-md-4">
							<label>
							<?php if (((Form::$post !== null )) && !empty(Form::$post['distance'])) {?>
							<?php ob_start();
echo $_smarty_tpl->tpl_vars['item']->value['name'];
$_prefixVariable1 = ob_get_clean();
echo Form::input2(array("type"=>"checkbox","name"=>"distance[".((string)$_smarty_tpl->tpl_vars['item']->value['id'])."]","label"=>$_prefixVariable1,"class"=>"distance-checkbox","value"=>Form::$post['distance']));?>

							<?php } else { ?>
							<?php echo Form::input2(array("type"=>"checkbox","name"=>"distance[".((string)$_smarty_tpl->tpl_vars['item']->value['id'])."]","label"=>$_smarty_tpl->tpl_vars['item']->value['name'],"class"=>"distance-checkbox"));?>

							<?php }?>
							</label>
							</div>
							<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?><div class="clearfix"></div><?php }?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<?php if ((isset($_smarty_tpl->tpl_vars['improvement']->value))) {?><h4><?php echo Language::get("objects","objects_improvement_text");?>
</h4><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['improvement']->value, 'item');
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
							<div class="col-md-4">
							<label>
							<?php if (!empty(Form::$post['improvement'])) {?>
							<?php ob_start();
echo $_smarty_tpl->tpl_vars['item']->value['name'];
$_prefixVariable2 = ob_get_clean();
echo Form::input2(array("type"=>"checkbox","name"=>"improvement[".((string)$_smarty_tpl->tpl_vars['item']->value['id'])."]","label"=>$_prefixVariable2,"class"=>"improvement-checkbox","value"=>Form::$post['improvement']));?>

							<?php } else { ?>
							<?php ob_start();
echo $_smarty_tpl->tpl_vars['item']->value['name'];
$_prefixVariable3 = ob_get_clean();
echo Form::input2(array("type"=>"checkbox","name"=>"improvement[".((string)$_smarty_tpl->tpl_vars['item']->value['id'])."]","label"=>$_prefixVariable3,"class"=>"improvement-checkbox"));?>

							<?php }?>
							</label>
							</div>
							<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?><div class="clearfix"></div><?php }?>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="text-right">
				<button id="advancedSearch" type="button" class="btn btn-secondary btn-adv-search"><?php echo Language::get("objects","btn_search_adv");?>
 <em class="fa fa-caret-down"></em></button>
				<button id="submitSearch" type="button" class="btn btn-warning btn-search" data-content="Wybierz jedną z opcji, według której chcesz rozpocząć wyszukiwanie"><?php echo Language::get("objects","btn_search");?>
 <em class="fa fa-search"></em></button>
			</div>
			</div>
	</div>
</div>
<?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['app_path']->value)."templates/website/schema/objects/promoted.smarty", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
<div id="search-results" class="container">
	<fieldset>
		<?php if ((isset($_smarty_tpl->tpl_vars['results']->value))) {?>
		<legend><?php echo Language::get("cms","search_results");?>
: <?php echo $_smarty_tpl->tpl_vars['counted_results']->value;?>
 <?php echo Language::get("cms","search_results_2");
if ($_smarty_tpl->tpl_vars['counted_results']->value >= 1 && $_smarty_tpl->tpl_vars['counted_results']->value <= 4) {
echo Language::get("cms","search_results_2a");
} elseif ($_smarty_tpl->tpl_vars['counted_results']->value >= 5) {
echo Language::get("cms","search_results_2b");
}?></legend>

		<?php echo Paginate::get();?>

		<div class="row">
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['results']->value, 'item', false, NULL, 'lp', array (
  'iteration' => true,
));
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_lp']->value['iteration']++;
?>
			<div class="col-xs-6 col-sm-4 col-md-3">
				<div class="item<?php if ($_smarty_tpl->tpl_vars['item']->value['search_expire'] > $_smarty_tpl->tpl_vars['now']->value) {?> promoted<?php }?>">
					<div class="img-figure">
						<div class="cat"><?php echo ObjectsTypes::getName($_smarty_tpl->tpl_vars['item']->value['type']);?>
</div>
						<?php if (!(isset($_smarty_tpl->tpl_vars['config']->value['basic']))) {
if ($_smarty_tpl->tpl_vars['item']->value['has_video'] == true) {?><div class="cat-video">wideo</div><?php }
}?>
						<a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
noclegi/<?php echo Kernel::rewrite($_smarty_tpl->tpl_vars['item']->value['city']);?>
/<?php echo Kernel::rewrite($_smarty_tpl->tpl_vars['item']->value['name']);?>
-i<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
"><img alt="<?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
" src="<?php echo ObjectsPhotos::getImage($_smarty_tpl->tpl_vars['item']->value['id']);?>
"></a>
						<?php if (!(isset($_smarty_tpl->tpl_vars['config']->value['basic']))) {
if (ObjectsRooms::getAmount($_smarty_tpl->tpl_vars['item']->value['id'])) {?><div class="cat-price">cena od <?php echo ObjectsRooms::getAmount($_smarty_tpl->tpl_vars['item']->value['id']);?>
</div><?php }
}?>
					</div>
					<div class="title">
						<h1><a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
noclegi/<?php echo Kernel::rewrite($_smarty_tpl->tpl_vars['item']->value['city']);?>
/<?php echo Kernel::rewrite($_smarty_tpl->tpl_vars['item']->value['name']);?>
-i<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</a></h1>
						<h5><a class="city-link" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
noclegi/<?php echo Kernel::rewrite($_smarty_tpl->tpl_vars['item']->value['city']);?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['city'];?>
</a></h5>
					</div>
					<div class="description"></div>
				</div>
			</div>
			<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_lp']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_lp']->value['iteration'] : null)%4 == 0) {?></div><br/><div class="row"><?php }?>
			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		</div>
		<?php echo Paginate::get();?>

		<?php } else { ?>
		<p><?php echo Language::get("cms","search_query_empty");?>
</p>
		<?php }?>
	</fieldset>
</div>
<?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['app_path']->value)."templates/website/schema/panels/bottom.smarty", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
}
}
