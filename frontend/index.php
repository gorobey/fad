<?php
if(!isset($status)){
	header('Location:../');
}else{
	//create path for mod_rewrite
	$install_dir = str_replace($_SERVER['HTTP_HOST'], "", ROOT_URL);
	$tree = explode("/", ltrim(str_replace($install_dir, "", rtrim($_SERVER['REQUEST_URI'], "/")), "/)"));
	$dir_count = count($tree);
	$path="";
	if($tree[0] != str_replace("/", "", $install_dir)){
		for($i=1;$i<=$dir_count;$i++){
			$path .= "../";
		}
	}
	require_once('header.php');
	if($path == ""){
		require_once('home.php');
	}else{
		require_once('contents.php');
	}
	require_once('footer.php');
}
