<?php
if(file_exists("../install/")){
	header("Location:../");
}else{
	require_once( "../config.php");
	require_once( "../system/includes/auth.lib.php");
	require_once( "../system/includes/license.lib.php");
	require_once("../system/includes/utils.lib.php");
	list($status, $user) = auth_get_status();
	if($status === AUTH_NOT_LOGGED){
		require_once('../login/connect.php');
	}elseif($status === AUTH_LOGGED){
		$user_id = $user['id'];
		if(can_access($user_id, 'admin')){
			require('header.php');
			require('dashboard.php');
			require('footer.php');
		}else{
			header("Location:../");
		}
	}else{
		die();
	}
}
