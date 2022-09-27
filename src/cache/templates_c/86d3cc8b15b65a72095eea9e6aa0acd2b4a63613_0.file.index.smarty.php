<?php
/* Smarty version 4.2.1, created on 2022-09-27 19:02:42
  from '/var/www/html/templates/website/index.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_633348d26e6701_39759563',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '86d3cc8b15b65a72095eea9e6aa0acd2b4a63613' => 
    array (
      0 => '/var/www/html/templates/website/index.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_633348d26e6701_39759563 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/vendor/smarty/smarty/libs/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<!DOCTYPE html>
<html lang="<?php echo Language::$selected;?>
">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Cache-Control" content="public, max-age:180">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/images/favicon.ico" type="image/x-icon">
	<title><?php if ((isset($_smarty_tpl->tpl_vars['meta']->value['title']))) {
echo $_smarty_tpl->tpl_vars['meta']->value['title'];
}?></title>
	<meta name="keywords" content="<?php if ((isset($_smarty_tpl->tpl_vars['meta']->value['keywords']))) {
echo $_smarty_tpl->tpl_vars['meta']->value['keywords'];
}?>" />
	<meta name="description" content="<?php if ((isset($_smarty_tpl->tpl_vars['meta']->value['description']))) {
echo $_smarty_tpl->tpl_vars['meta']->value['description'];
}?>" />
	<meta name="author" content="Grzegorz Miskiewicz - www.avatec.pl" />
	<meta name="copyright" content="&copy; 2016-<?php echo smarty_modifier_date_format(time(),"%Y");?>
 Avatec" />
	<meta name="robot" content="<?php if ((isset($_smarty_tpl->tpl_vars['meta']->value['index']))) {?>index<?php } else { ?>noindex<?php }?>,<?php if ((isset($_smarty_tpl->tpl_vars['meta']->value['follow']))) {?>follow<?php } else { ?>nofollow<?php }?>" />

	<meta property="og:url"           content="<?php echo $_smarty_tpl->tpl_vars['app_request_url']->value;?>
" />
	<meta property="og:type"          content="website" />
	<meta property="og:title"         content="<?php if ((isset($_smarty_tpl->tpl_vars['meta']->value['title']))) {
echo $_smarty_tpl->tpl_vars['meta']->value['title'];
}?>" />
	<meta property="og:description"   content="<?php if ((isset($_smarty_tpl->tpl_vars['meta']->value['description']))) {
echo $_smarty_tpl->tpl_vars['meta']->value['description'];
}?>" />
	<meta property="og:image"         content="<?php if (((Kernel::$facebook_image !== null ))) {
echo Kernel::$facebook_image;
} else {
echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/images/facebook.jpg<?php }?>" />

	<link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/css/style.css" rel="stylesheet">
	<link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/css/bootstrap-select.min.css" rel="stylesheet" media="screen">
	<link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/css/font-awesome.min.css" rel="stylesheet">
	<link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/css/flags/css/flag-icon.min.css" rel="stylesheet" media="screen">
	<link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/css/checkbox-x.min.css" rel="stylesheet" media="screen">
	<?php if (Kernel::$Alertify == true) {?>
	<link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
plugins/alertify/css/alertify.core.css" rel="stylesheet" media="screen">
	<link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
plugins/alertify/css/alertify.default.css" rel="stylesheet" media="screen">
	<?php }?>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	      <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/js/html5shiv.min.js"><?php echo '</script'; ?>
>
	      <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/js/respond.min.js"><?php echo '</script'; ?>
>
    <![endif]-->

    <?php echo '<script'; ?>
 type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"><?php echo '</script'; ?>
>
	<?php if (Kernel::$Alertify == true) {?>
	<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
plugins/alertify/alertify.min.js"><?php echo '</script'; ?>
>
	<?php }?>
	<?php if (Kernel::$CkEditor == true) {?>
	<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
include/js/ckeditor/ckeditor.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
include/js/ckeditor/adapters/jquery.js"><?php echo '</script'; ?>
>
	<?php }?>

    <?php if (!empty($_smarty_tpl->tpl_vars['css']->value)) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['css']->value, 'item');
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
	<link href="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
" rel="stylesheet" media="screen">
	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}?>

	<?php if (!empty($_smarty_tpl->tpl_vars['assets']->value['css'])) {?>
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['assets']->value['css'], 'item');
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
	<link href="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
" rel="stylesheet" media="screen">
	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	<?php }?>

	<?php if (Kernel::$GoogleMaps == true) {?>
	<?php echo '<script'; ?>
 src="https://maps.googleapis.com/maps/api/js<?php if ((isset($_smarty_tpl->tpl_vars['config']->value['google_api_key']))) {?>?key=<?php echo $_smarty_tpl->tpl_vars['config']->value['google_api_key'];?>
&<?php } else { ?>?<?php }?>v=3.exp"><?php echo '</script'; ?>
>
	<?php }?>
	<?php if ((isset($_smarty_tpl->tpl_vars['config']->value['google_tools']))) {
echo $_smarty_tpl->tpl_vars['config']->value['google_tools'];
}?>

	<?php if (!empty($_smarty_tpl->tpl_vars['config']->value['facebook_messager'])) {?>
		<?php if ($_smarty_tpl->tpl_vars['config']->value['facebook_messager'] == "TRUE" && (isset($_smarty_tpl->tpl_vars['config']->value['facebook_app_id'])) && (isset($_smarty_tpl->tpl_vars['config']->value['facebook_page_id']))) {?>
		<div id="fb-root"></div>
		<?php echo '<script'; ?>
 async defer src="https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));<?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
>
		  window.fbAsyncInit = function() {
		    FB.init({
		      appId            : '<?php echo $_smarty_tpl->tpl_vars['config']->value['facebook_app_id'];?>
',
		      autoLogAppEvents : true,
		      xfbml            : true,
		      version          : 'v3.3'
		    });
		  };
		<?php echo '</script'; ?>
>

		<div class="fb-customerchat" attribution=setup_tool page_id="<?php echo $_smarty_tpl->tpl_vars['config']->value['facebook_page_id'];?>
"></div>
		<?php }?>
	<?php }?>

	<?php echo '<script'; ?>
 async src="https://cdn.ampproject.org/v0.js"><?php echo '</script'; ?>
>

	<?php if (!empty($_smarty_tpl->tpl_vars['config']->value['facebook_app_id'])) {?><div id="fb-root"></div><?php echo '<script'; ?>
 async defer crossorigin="anonymous" src="https://connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v3.3&appId=<?php echo $_smarty_tpl->tpl_vars['config']->value['facebook_app_id'];?>
&autoLogAppEvents=1"><?php echo '</script'; ?>
><?php }?>
</head>
<body>

	<?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['app_path']->value)."templates/website/body.smarty", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/js/bootstrap.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/js/checkbox-x.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/js/bootstrap-select.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/js/cookieww/cookieww.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript">
	$(document).ready(function() {
		$('body').cmsciw({
			text: "<?php echo Language::get('cms','cookie_bar_text');?>
" + " <a href=\"<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
polityka-prywatnosci\"><?php echo Language::get('cms','cookie_bar_link');?>
</a>",
			button: '<span class="fa fa-check"></span> <?php echo Language::get('cms','cookie_bar_link_text');?>
'
		});
	});
	<?php echo '</script'; ?>
>
	
	<?php if (!empty($_smarty_tpl->tpl_vars['javascript']->value)) {?>
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['javascript']->value, 'item');
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
	<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
"><?php echo '</script'; ?>
>
	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	<?php }?>

	<?php if (!empty($_smarty_tpl->tpl_vars['assets']->value['js'])) {?>
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['assets']->value['js'], 'item');
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
	<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
"><?php echo '</script'; ?>
>
	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	<?php }?>

	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/website/js/script.js"><?php echo '</script'; ?>
>
	<?php if ((isset($_smarty_tpl->tpl_vars['config']->value['google_stats']))) {
echo '<script'; ?>
>
	(function(i,s,o,g,r,a,m){ i['GoogleAnalyticsObject']=r; i[r]=i[r]||function() { (i[r].q=i[r].q||[]).push(arguments) },i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');ga('create', '<?php echo $_smarty_tpl->tpl_vars['config']->value['google_stats'];?>
', 'auto'); ga('send', 'pageview');
	<?php echo '</script'; ?>
>
	<?php }?>
</body>
</html>
<?php }
}
