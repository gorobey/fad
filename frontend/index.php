<?php
	$path = path();
	$position = count(explode("/", $path));
	require_once('header.php');
	if($position == 1){
		require_once('home.php');
	}elseif($position == 2 || $position == 3 ){
		require_once('taxonomy.php');//sistemare listato corsi / lezioni (type/subtype) // can_access
	}elseif($position == 4){
		require_once('contents.php');
	} 
	require_once('footer.php');
