<?php
require_once( "../../../config.php");
require_once("../../../system/includes/utils.lib.php");
require_once( "../../../system/includes/auth.lib.php");
require_once( "../../../system/includes/license.lib.php");
if(!isset($status)){auth_check_point();}

$attrToedit = isset($_POST['id']) ? $_POST['id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : 0;
if($_POST['dimension'] === "roles" || $_POST['dimension'] === "groups"){
	$dimension = $_POST['dimension'];
}else{
	$dimension = '';
}

if($action=="e" && $attrToedit!=0){//edit
	echo license_edit($attrToedit, $_POST['name'], $dimension);
}elseif($action=="d" && $attrToedit!=0){//delete
	echo license_del($attrToedit, $dimension);
}elseif($action=="n"){//new
	echo $addAttr = license_add($_POST['name'], $dimension);
}else{
	die('<div class="text-center alert alert-danger" role="alert">'._("Error: unsupported action!").'</div>');
}