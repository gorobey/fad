<?php
if(!isset($status)){
	header('Location:../');
}else{
	require_once('header.php');
	require_once('home.php');
	require_once('footer.php');
}