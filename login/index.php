<?php
require_once( "../config.php");
require_once( ROOT."/system/includes/utils.lib.php");
require_once( ROOT."/system/includes/auth.lib.php");
require_once( ROOT."/system/includes/license.lib.php");
list($status, $user) = auth_get_status();
if($status == AUTH_LOGGED && auth_get_option("TRANSICTION METHOD") == AUTH_USE_LINK){
	$link = "?uid=".$_GET['uid'];
}else{
	$link = '';
}
$referer = "../".basename(__DIR__);
if ($status === AUTH_NOT_LOGGED){
	require_once('connect.php');
}elseif($status === AUTH_LOGGED){
	if(isset($_GET['redirect'])){
		$redirect = $_GET['redirect'];
	}else{
		$redirect = "../";
	}
	header('Location:../'.$redirect);
}else{
	die();
}