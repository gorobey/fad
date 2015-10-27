<?php if (count(get_included_files()) === 1) die();
require_once( "../config.php");
require_once( ROOT."/system/includes/utils.lib.php");
require_once(ROOT."/system/includes/auth.lib.php");
list($status, $user) = auth_get_status();
if($status == AUTH_NOT_LOGGED || $status == AUTH_FAILED || $status == AUTH_INVALID_PARAMS || $status == AUTH_FAILED || $status == AUTH_BRUTE_FORCE){	
?>
<!DOCTYPE html>
<html lang="<?php echo substr($_SESSION['locale'], 0, 2); ?>">
	<head>
		<title><?php echo get_info('title')." | ".get_info('description'); ?>: Login</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0"/>
		<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
		<meta http-equiv="robots" content="noindex,nofollow" />
		<meta name="author" Content="Giulio Gorobey">
		<meta name="Description" content="Platform: Login" />
		<link rel="shortcut icon" href="../system/style/imgs/favicon.png" />
		<link href="../system/style/css/bootstrap.min.css" rel="stylesheet" />
		<link href="../system/style/css/custom.css" rel="stylesheet" />
	</head>
	<body class="login">
	<div class="container form_box">
		<div class="row">
			<div class="col-xs-12 text-center">
				<img src="../system/style/imgs/logo.png" />
				<h1>Entra nel mondo de L'Ippogrifo <sup>&reg;</sup></h1>
				<h2>PIATTAFORMA PER LA FORMAZIONE A DISTANZA</h2>
				<div id="connect">
<?php
if(!isset($_GET['q'])){
	$q="";
}elseif ($_GET['q']=="LOGOUT" || $_GET['q']=="INVALID" || $_GET['q']=="FAILED" || $_GET['q']=="CONFIRMED" || $_GET['q']=="RESET" || $_GET['q']=="EXPIRED" || $_GET['q']=="EDIT" || $_GET['q']=="FORGOT" || $_GET['q']=="REGISTER" || $_GET['q']=="BAN" || $_GET['q']==""){
	$q=$_GET['q'];
}else{
	$q="";
}

$reset = false;

if(isset($_POST['redirect'])){
	$redirect = $_POST['redirect'];
}elseif(isset($_GET['redirect'])){
	$redirect = $_GET['redirect'];
}else{
	$redirect = "";	
}

if($_CONFIG["register"] === TRUE) {
	$register = '
	<div class="text-center">
		<hr />
		<a href="#" onclick="parent.location=\'index.php?q=REGISTER\';return false;">'._("New User? Register now!").'</a>
		<hr />
	</div>';
}

if($_CONFIG["MAINTENANCE_MODE"] === TRUE) echo '<div class="text-center alert alert-danger" role="alert">'._("System upgrade, please be patient!").'</div>';

if($q=="CONFIRMED"){
		echo '<div class="text-center alert alert-success" role="alert">'._("Your registration has been confirmed, you can now login!").'</div>';}
if($q=="EXPIRED"){
		echo '<div class="text-center alert alert-warning" role="alert">'._("Registration can not be confirmed, as has expired!").'</div>';}
if($q=="LOGOUT"){
		echo '<div class="text-center alert alert-info" role="alert">'._("Logout successfully performed!").'</div>';}
elseif($q=="INVALID"){
		echo '<div class="text-center alert alert-warning" role="alert">'._("Login Failed: you have entered invalid data!").'</div>';}
elseif($q=="FAILED"){
		echo '<div class="text-center alert alert-danger" role="alert">'._("Internal Error: the system may be unavailable, please try again later!").'</div>';}
elseif($q=="FORGOT"){
		echo '<div class="text-center alert alert-info" role="alert">'._("Enter your e-mail for recover your password!").'</div>';}
elseif($q=="REGISTER"){
		echo '<div class="text-center alert alert-info" role="alert">'._("Create a new account!").'</div>';}
elseif($q=="BAN"){
		echo '<div class="text-center alert alert-warning" role="alert">'._("Too many attempts, user temporarily suspended!").'</div>';}
elseif($q=="RESET"){
	if(isset($_POST['mail'])){
			require_once(ROOT."/system/includes/reg.lib.php");
			send_edit_link($_POST['mail']);
	}else{
		echo '<div class="text-center alert alert-warning" role="alert">'._("Error: mail can't be empty!").'</div>';
	}
}elseif($q=="EDIT" && isset($_GET['uid']) && isset($_GET['mail']) && mail_exists($_GET['mail']) == 1 ){
	$reset = true;
	require_once(ROOT."/system/includes/reg.lib.php");
	edit_passwd($_GET['uid'], $_GET['mail']);
	echo '<div class="notification-area"></div><!--notification-area-->';
} ?>
					
<?php
if(!isset($q) || $q=="LOGOUT" || $q=="INVALID" || $q=="FAILED" || $q=="CONFIRMED" || $q=="RESET" || $q=="EXPIRED" || $q=="EDIT" || $q==""){ ?>
	<form name="login" id="login" action="../login/login.php" method="POST">
		<input placeholder="mail" name="mail" id="mail" value="" class="text-center form-control" type="text" autocomplete="off" tabindex="1">
		<input placeholder="*****" name="passwd" id="password" value="" class="text-center form-control" type="password" tabindex="2">
		<button name="action" class="btn btn-first-action  form-control-50 left" type="submit" tabindex="3">Login</button>
		<button type="submit" class="btn btn-second-action  form-control-50 right" type="submit" tabindex="4" onclick="parent.location='index.php?q=FORGOT';return false;"><?php echo _("Lost password?");?></button>
		<input name="redirect" id="redirect" value="<?php echo $redirect;?>" type="hidden">
<?php
}
if($q=="FORGOT"){ ?>
	<form id="forgot" name="forgot" action="./index.php?q=RESET" method="POST">
		<input placeholder="mail" name="mail" id="mail" value="" class="text-center form-control" type="text" autocomplete="off" tabindex="1">
		<button name="action" class="btn btn-first-action form-control-50 left" type="submit" tabindex="2"><?php echo _("Reset password");?></button>
        <button type="submit" class="btn btn-second-action form-control-50 right" tabindex="3" onclick="parent.location='./';return false;"><?php echo _("Login");?></button>
		<input name="q" id="q" value="RESET" type="hidden">
<?php }
if($q=="REGISTER" && $_CONFIG['register']===true){ ?>
	<form id="register" name="register" action="register.php" method="POST">
		<input placeholder="Name" type="text" name="name" class="text-center form-control" tabindex="1">
		<input placeholder="Surname" type="text" name="surname" class="text-center form-control" tabindex="2">
		<input placeholder="Mail" type="text" name="mail" class="text-center form-control" autocomplete="off" tabindex="3">
		<button name="action" class="btn btn-first-action form-control-50 left" tabindex="4"><?php echo _("Register");?></button>
        	<button type="submit" class="btn btn-second-action form-control-50 right" tabindex="5" onclick="parent.location='./';return false;"><?php echo _("Login");?></button>
        <?php } ?>

	</form>
</div><!--form_box-->
<?php $to = date("Y"); ?>
<div class="text-center footer-form">
	<?php
			if($_CONFIG['register'] === true){
			echo $register;
		}	
	?>
<?php echo _("This Software is licensed under");?>:<a href="http://it.wikipedia.org/wiki/GNU_General_Public_License" target="_blank">"GNU/GPL"</a><br />Copyright © <a rel="author" href="http://gorobey.it" target="_blank">Giulio Gorobey</a> 2014<?php if ($to != "2014"){echo "-".$to;} ?><br />
<?php echo _("Please! Help to develop");?>: <a href="mailto:gorobey@ippogrifogroup.com" target="_bank"><?php echo _("Report Bugs");?></a> - <strong>V.<?php echo file_get_contents(ROOT.'/version.txt', true);?></strong><hr />
	<div class="text-right">
	<?php echo _("Language");?>:
	<?php lang_menu(); ?>
	</div>
</div>
		</div>
	</div>
</div>
<?php if($reset === true){ ?>
<script src="../system/js/jquery.min.js"></script>
<script>
$(document).ready(function () {
	$("span.new_pass").on("click", function () {
		var pass = $(this).html();
		//alert(pass);
		$("#password").val('').val(pass);
		$(".notification-area").html('<div class="alert alert-info" role="alert"><?php echo _("Key paste into password input"); ?></div>');
	});
});
</script>
<?php } ?>
</body>
</html>
<?php
}else{
	header('Location:../index.php');
}
