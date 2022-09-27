<?php
/* Smarty version 4.2.1, created on 2022-09-27 20:46:55
  from '/var/www/html/install/templates/index.smarty' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_6333451f46a389_82994564',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b709c482babc2a2439322c369d217e3fd42e7c5d' => 
    array (
      0 => '/var/www/html/install/templates/index.smarty',
      1 => 1664303647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6333451f46a389_82994564 (Smarty_Internal_Template $_smarty_tpl) {
?><html>
<head>
	<meta charset="utf-8" />
	<meta name="author" content="Grzegorz Miskiewicz - www.avatec.pl" />
	<meta name="copyright" content="&copy; 2016 Avatec" />
	<meta name="robot" content="noindex,nofollow" />
	<title>Instalator Avatec Accomodation</title>
	<link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/css/bootstrap.min.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/css/bootstrap-select.min.css" rel="stylesheet" media="screen">
    <link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/css/checkbox-x.min.css" rel="stylesheet" media="screen">
    <link href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/css/style.css" type="text/css" rel="stylesheet" />

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	    <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/js/html5shiv.min.js"><?php echo '</script'; ?>
>
	    <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/js/respond.min.js"><?php echo '</script'; ?>
>
    <![endif]-->

    <?php echo '<script'; ?>
 type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/js/bootstrap.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/js/bootstrap-select.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/js/checkbox-x.min.js"><?php echo '</script'; ?>
>

    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
templates/js/installer.js"><?php echo '</script'; ?>
>
</head>
<body>

	<div id="installer-window">
		<div class="container">
			<h1 class="text-primary">Instalator oprogramowania<span>Avatec Accomodation</span></h1>

			<?php if (!(isset($_GET['page']))) {?>
			<h4 class="text-center text-info">Konfiguracja Twojego serwera</h4>

				<table class="table table-bordered">
					<tr>
						<th>Wersja PHP:</th><td><?php echo $_smarty_tpl->tpl_vars['extensions']->value['php_version'];?>
</td>
					</tr><tr>
						<th>Curl:</th><td><?php if ($_smarty_tpl->tpl_vars['extensions']->value['curl'] == 1) {?><span class="label label-success">ok</span><?php } else { ?><span class="label label-danger">błąd krytyczny</span><?php }?></td>
					</tr><tr>
						<th>Imagick:</th><td><?php if ($_smarty_tpl->tpl_vars['extensions']->value['imagick'] == 1) {?><span class="label label-success">ok</span><?php } else { ?><span class="label label-danger">brak</span><?php }?></td>
					</tr><tr>
						<th>GD:</th><td><?php if ($_smarty_tpl->tpl_vars['extensions']->value['gd'] == 0) {?><span class="label label-success">ok</span><?php } else { ?><span class="label label-danger">brak</span><?php }?></td>
					</tr><tr>
						<th>mod_rewrite:</th><td><?php if ($_smarty_tpl->tpl_vars['extensions']->value['mod_rewrite'] == 1) {?><span class="label label-success">ok</span><?php } else { ?><span class="label label-danger">nie wykryto mod_rewrite - może to być spowodowane działaniem php w trybie FPM/lub jako proces CGI co uniemożliwia detekcje</span><?php }?></td>
					</tr>
				</table>

			<div class="alert alert-info text-center">
				Instalując to oprogramowanie akceptujesz <a title="Kliknij, aby przeczytać" target="_blank" href="http://www.avatec.pl/pdf/accomodation-license.pdf">treść licencji</a>, która została dołączona do tego oprogramowania.<br/>Autor <b>nie ponosi odpowiedzialności</b> za szkody wynikłe z użytkowania tego oprogramowania.<br/>W przypadku napotkania błędów, prosimy o kontakt z <a target="_blank" title="Kliknij, aby przejść do strony kontaktu" href="http://www.avatec.pl/kontakt">Biurem Obsługi Klienta</a>.
				<hr/>
				<a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
?page=start" class="btn btn-primary"><span class="fa fa-check"></span> rozumiem, chcę zainstalować to oprogramowanie</a>
			</div>
			<?php } else { ?>
				<?php if ((isset($_smarty_tpl->tpl_vars['Ok']->value)) && $_smarty_tpl->tpl_vars['Ok']->value == true) {?>
				<?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['app_path']->value)."templates/finish.smarty", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
				<?php } else { ?>
				<?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['app_path']->value)."templates/form.smarty", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
				<?php }?>
			<?php }?>

		</div>
	</div>

</body>
</html>
<?php }
}
