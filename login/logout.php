<?php
require_once("../config.php");
require_once("../system/includes/auth.lib.php");
list($status, $user) = auth_get_status();
if($status == AUTH_LOGGED){
	if(auth_logout()){
		header('Location:../?s=LOGOUT');
	}
}else{
	header('Location:../');
}
?>
