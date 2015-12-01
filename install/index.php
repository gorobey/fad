<?php
if(count(get_included_files()) ==1) {
	header('Location:../');
	exit;
}?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>FAD Platform: Install</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0"/>
		<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
		<meta http-equiv="robots" content="noindex,nofollow" />
		<meta name="author" Content="Giulio Gorobey">
		<meta name="Description" content="Platform: Installer" />
		<link rel="shortcut icon" href="system/style/imgs/fav.ico" />
		<link href="system/style/css/bootstrap.min.css" rel="stylesheet" />
		<link href="system/style/css/custom.css" rel="stylesheet" />
	</head>
	<body class="install">
	<div class="container form_box">
		<div class="row">
			<div class="col-sx-12 text-center">
				<img src="system/style/imgs/logo.png" />
				<h1>Entra nel mondo de L'Ippogrifo <sup>&reg;</sup></h1>
				<h2>PIATTAFORMA PER LA FORMAZIONE A DISTANZA</h2>
				<div id="installer">
<?php
$infos = array();
$errors = array();
$warnings = array();
$cinfo = '<div class="text-center alert alert-info" role="alert">';
$cwarning = '<div class="text-center alert alert-warning" role="alert">';
$cerror = '<div class="text-center alert alert-danger" role="alert">';

$conf_empty = $cerror;
foreach($_CONFIG as $key => $conf){
	if($conf==""){
		$conf_empty .= "<strong>".$key."</strong>: cannot be empty<br />";
		array_push($errors, "1");
	}
}
if(count($errors)>0){
	echo $conf_empty."</div>";
}

$tableCheck = mysqli_query($db_conn, "SHOW TABLES LIKE \"". $_CONFIG['table_prefix']."%\"");
$tableExists = mysqli_num_rows($tableCheck);
if($tableExists>0){
	array_push($errors, "1");
	echo $cerror."Intallation already performed!"."</div>";
	$newname = '.installed_'.rand();
	rename(dirname(__FILE__), $newname);
}

//$link = mysqli_connect($_CONFIG['DB_HOST'], $_CONFIG['DB_USER'], $_CONFIG['DB_PASS']);
//if (!$link) {
//	echo $cerror."Could not connect to the server '" . $_CONFIG['DB_HOST'] . "'<hr />
//	<small>".mysqli_error()."</small></div>";
//	array_push($errors, "1");
//}

if(function_exists('apache_get_modules')){
	$modules = apache_get_modules();
	if (!extension_loaded('gd')  && !function_exists('gd_info')) {
		echo $cerror."PHP GD library is NOT installed on your web server</div>";
		array_push($errors, "1");
	}
	if (!in_array('mod_rewrite', $modules)) {
		echo $cerror."mod_rewrite is NOT avanable on your web server</div>";
		array_push($errors, "1");
	}
}

if(!mail('admin@'.$_SERVER['SERVER_NAME'], "Test Postfix", "Test mail from postfix")){
	echo $cerror."Postfix is required to continue</div>";
	array_push($errors, "1");
}
if(count($errors)==0){ ?>
	<form name="install" id="install" action="install/install.php" method="POST">
		<input placeholder="Name of installation" name="title" id="title" value="" class="text-center form-control" type="text" autocomplete="off" tabindex="1">
		<input placeholder="Description" name="site_desc" id="site_desc" value="" class="text-center form-control" type="text" autocomplete="off" tabindex="2">						
		<input placeholder="Admin Name" name="name" id="name" value="" class="text-center form-control" type="text" autocomplete="off" tabindex="3">
		<input placeholder="Admin Surname" name="surname" id="surname" value="" class="text-center form-control" type="text" autocomplete="off" tabindex="4">
		<input placeholder="Admin E-mail" name="mail" id="mail" value="" class="text-center form-control" type="text" autocomplete="off" tabindex="5">
		<button name="action" id="submit" class="btn btn-first-action  form-control" type="submit" tabindex="6">Start installation!</button>
		<input name="version" type="hidden" value="<?php echo file_get_contents(ROOT.'/version.txt', true);?>" />
		<input name="install" type="hidden" value="install" />
	</form>
<?php }
$to = date("Y"); ?>
<div class="text-center footer-form">
This Software is licensed under:<a href="http://it.wikipedia.org/wiki/GNU_General_Public_License" target="_blank">"GNU/GPL"</a> &bull; Copyright © <a rel="author" href="http://gorobey.it" target="_blank">Giulio Gorobey</a> 2014<?php if ($to != "2014"){echo "-".$to;} ?><br />
Please! Help to develop: <a href="mailto:gorobey@ippogrifogroup.com" target="_bank">Report Bugs</a> &bull; <a href="changelog.txt" target="_blank">V.<?php echo file_get_contents(ROOT.'/version.txt', true);?></a>
</div>
		</div>
	</body>
</html>
