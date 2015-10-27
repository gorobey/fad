<?php
include_once("../config.php");
if($_CONFIG['register']!==TRUE || !isset($_POST['mail'])){
	header('Location:index.php');
	die();
}
include_once("../system/includes/utils.lib.php");
include_once("../system/includes/reg.lib.php");
$_POST['group']=primary_group();
$rec = reg_register($_POST); ?>
<html>
	<head>
		<title><?php echo get_info('title')." | ".get_info('description'); ?>: Login</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0"/>
		<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
		<meta http-equiv="robots" content="noindex,nofollow" />
		<meta name="author" Content="Giulio Gorobey">
		<meta name="Description" content="Platform: Login" />
		<link rel="shortcut icon" href="../system/style/imgs/fav.ico" />
		<link href="../system/style/css/bootstrap.min.css" rel="stylesheet" />
		<link href="../system/style/css/custom.css" rel="stylesheet" />
	</head>
	<body class="login">
	<div class="container form_box">
		<div class="row">
			<div class="col-sx-12 text-center">
				<img src="../system/style/imgs/logo.png" />
				<h1>Entra nel mondo de L'Ippogrifo <sup>&reg;</sup></h1>
				<h2>PIATTAFORMA PER LA FORMAZIONE A DISTANZA</h2>
				<div id="connect">

					<?php
					if($rec === REG_FAILED){
						echo '<div class="alert alert-danger" role="alert">'._("Error: Registration failed!").'</div>'; ?>
						<form name="login" id="login" action="../login/login.php" method="POST">
							<input placeholder="mail" name="mail" id="mail" value="" class="text-center form-control" type="text" autocomplete="off" tabindex="1">
							<input placeholder="*****" name="passwd" id="password" value="" class="text-center form-control" type="password" tabindex="2">
							<button name="action" class="btn btn-first-action  form-control-50 left" type="submit" tabindex="3">Login</button>
							<button type="submit" class="btn btn-second-action  form-control-50 right" type="submit" tabindex="4" onclick="parent.location='index.php?q=FORGOT';return false;"><?php echo _("Lost password?");?></button>
							<?php echo $register; ?>
							<input name="redirect" id="redirect" value="<?php echo $redirect;?>" type="hidden">
					<?php
					}elseif($rec === REG_SUCCESS){
						echo '<div class="alert alert-info" role="alert">'._("Registration completed, comfirm link sent!").'</div>'; ?>
						<form id="register" name="register" action="register.php" method="POST">
							<input placeholder="Name" type="text" name="name" class="text-center form-control" tabindex="1">
							<input placeholder="Surname" type="text" name="surname" class="text-center form-control" tabindex="2">
							<input placeholder="Mail" type="text" name="mail" class="text-center form-control" autocomplete="off" tabindex="3">
							<button name="action" class="btn btn-first-action form-control-50 left" tabindex="4"><?php echo _("Register");?></button>
								<button type="submit" class="btn btn-second-action form-control-50 right" tabindex="5" onclick="parent.location='./';return false;"><?php echo _("Login");?></button>
							<?php
							
					}else{
						echo '<div class="alert alert-danger" role="alert">'._("Error: Contact the admin!").'</div>';
					} ?>
					</form>
</div><!--form_box-->
<?php $to = date("Y"); ?>
<div class="text-center footer-form">
<?php echo _("This Software is licensed under");?>:<a href="http://it.wikipedia.org/wiki/GNU_General_Public_License" target="_blank">"GNU/GPL"</a> &bull; Copyright © <a rel="author" href="http://gorobey.it" target="_blank">Giulio Gorobey</a> 2014<?php if ($to != "2014"){echo "-".$to;} ?><br />
<?php echo _("Please! Help to develop");?>: <a href="mailto:gorobey@ippogrifogroup.com" target="_bank"><?php echo _("Report Bugs");?></a> &bull; <a href="changelog.txt" target="_blank">V.<?php echo file_get_contents(ROOT.'/version.txt', true);?></a><hr />
	<div class="right">
	<?php echo _("Language");?>:
	<?php lang_menu(); ?>
	</div>
</div>
		</div>
	</div>
</div>
</body>
</html>
