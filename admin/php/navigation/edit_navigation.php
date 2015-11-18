<?php
require_once( "../../../config.php");
require_once( "../../../system/includes/auth.lib.php");
require_once( "../../../system/includes/license.lib.php");
require_once("../../../system/includes/utils.lib.php");
if(!isset($status)){$user_id = auth_check_point();}

//print_r($_POST['nav-tree']);


if(isJson($_POST['nav-tree'])){	
	edit_navigation($_SESSION['locale'], $_POST['nav-tree']);
}