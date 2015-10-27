<?php
include_once("../config.php");
include_once("../system/includes/utils.lib.php");
include_once("../system/includes/reg.lib.php");
if(isset($_GET['id']) && strlen($_GET['id']) == 32){
	reg_clean_expired();
	$confirm = reg_confirm($_GET['id']);
	switch($confirm){
		case REG_SUCCESS:
			header("Refresh: 0;URL=../index.php?q=CONFIRMED");
			break;
		case REG_FAILED:
			header("Refresh: 0;URL=../index.php?q=EXPIRED");
			break;
	}
}
