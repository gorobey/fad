<?php
require_once( "./config.php");
if(file_exists("./install/")){
	require_once("./install/index.php");
}else{
	require_once( "./system/includes/auth.lib.php");
	require_once("./system/includes/license.lib.php");
	require_once("./system/includes/utils.lib.php");
	list($status, $user) = auth_get_status();
	
	switch($status){
		case AUTH_NOT_LOGGED:
			$user_id = -1;
			if($_CONFIG['register']!==TRUE){header("Location:".protocol().ROOT_URL.'login');}
			else{require_once('frontend/index.php');}
		break;
		case AUTH_LOGGED:
			$user_id = $user['id'];
			require_once('frontend/index.php');
		break;
	}
}
