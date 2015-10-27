<?php
require_once("../config.php");
require_once("../system/includes/auth.lib.php");
//creazione funzione
list($status, $user) = auth_get_status(); //questo resta qui
if($status == AUTH_NOT_LOGGED){ // da qui inizia la funzione: le valiabili post devono essere passate alla funzione
	$mail = strtolower(trim($_POST["mail"]));
	$passwd = $_POST["passwd"];
	if($mail == "" || $passwd == ""){
		$status = AUTH_INVALID_PARAMS;
	}else{
		list($status, $user) = auth_login($mail, $passwd);
		if(!is_null($user)){
			list($status, $uid) = auth_register_session($user);
		}
	}
}// qui termina
$redirect = $_POST['redirect'];
switch($status){
	case AUTH_LOGGED:
		header("Location:../index.php");
	break;
	case AUTH_INVALID_PARAMS:
		header("Location:index.php?q=INVALID");
	break;
	case AUTH_BRUTE_FORCE:
		header("Location:index.php?q=BAN");
	break;
	case AUTH_LOGEDD_IN:
		switch(auth_get_option("TRANSICTION METHOD")){
			case AUTH_USE_LINK:
				header("Location:desktop/index.php?uid=".$uid);
			break;
			case AUTH_USE_COOKIE:
				setcookie('uid', $uid, time()+$_CONFIG['expire'], '/');
				header("Location:../");
			break;
			case AUTH_USE_SESSION:
				header("Location:../");
			break;
		}
	break;
	case AUTH_FAILED:
		header("Location:index.php?q=FAILED");
	break;
}