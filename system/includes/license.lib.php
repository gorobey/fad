<?php
function is_admin($user_id){//debugged
	global $_CONFIG, $db_conn;
	$result = mysqli_query($db_conn, "SELECT * FROM ".$_CONFIG['t_attr']." WHERE type = 'g' AND id = '".$user_id."' AND value IN (SELECT `".$_CONFIG['t_groups']."`.`id` FROM `".$_CONFIG['t_groups']."` WHERE `".$_CONFIG['t_groups']."`.`name`='admin')"
	);
	if(mysqli_num_rows($result) == 1){
		return true;
	}else{
		return false;
	}
}


function in_group($user_id, $group){//debugged
	global $_CONFIG, $db_conn;
	$result = mysqli_query($db_conn, "SELECT * FROM ".$_CONFIG['t_attr']." WHERE type = 'g' AND id = '".$user_id."' AND value IN (SELECT `".$_CONFIG['t_groups']."`.`id` FROM `".$_CONFIG['t_groups']."` WHERE `".$_CONFIG['t_groups']."`.`name`='".$group."')"
	);
	if(mysqli_num_rows($result) == 1){
		return true;
	}else{
		return false;
	}	
}


function has_role($user_id, $role){//debugged
	global $_CONFIG, $db_conn;
	$result = mysqli_query($db_conn, "SELECT * FROM ".$_CONFIG['t_attr']." WHERE type = 'r' AND id = '".$user_id."' AND value IN (SELECT `".$_CONFIG['t_roles']."`.`id` FROM `".$_CONFIG['t_roles']."` WHERE `".$_CONFIG['t_roles']."`.`name`='".$role."')"
	);
	if(mysqli_num_rows($result) == 1){
		return true;
	}else{
		return false;
	}		
}


function roles_list($user_id){
	global $_CONFIG, $db_conn;
	$result = mysqli_query($db_conn, "SELECT * FROM ".$_CONFIG['t_attr']." WHERE type = 'r' AND id = '".$user_id."'");
	$data = array();
	while($tmp = mysqli_fetch_assoc($result)){
		array_push($data, $tmp);
	};
	return $data;	
}


function arr_groups(){//debugged
	global $_CONFIG, $db_conn;
	$result = mysqli_query($db_conn, "SELECT * FROM ".$_CONFIG['t_groups']);
	$data = array();
	while($tmp = mysqli_fetch_assoc($result)){
		array_push($data, $tmp);
	}
	return $data;
}


function arr_roles(){//debugged
	global $_CONFIG, $db_conn;
	$result = mysqli_query($db_conn, "SELECT * FROM ".$_CONFIG['t_roles']);
	$data = array();
	while($tmp = mysqli_fetch_assoc($result)){
		array_push($data, $tmp);
	};
	return $data;
}




//questi qui sotto sono da sistemare
function license_change($userid, $perms){//da sistemare (da buttare?)
	global $_CONFIG, $db_conn;
	mysqli_query($db_conn, "
	UPDATE ".$_CONFIG['t_users']."
	SET level='".$perms."' WHERE id='".$userid."'
	");
}

function edit_group($uid, $group){
	global $_CONFIG, $db_conn;
	mysqli_query($db_conn, "
	UPDATE ".$_CONFIG['t_attr']."
	SET value='".$group."' WHERE id='".$uid."' AND id!='1' AND type = 'g' AND value != '".$group."'
	");
	if(mysqli_affected_rows($db_conn) == 1){
		return true;
	}else{
		return false;
	}
}

function edit_roles($uid, $role){
	global $_CONFIG, $db_conn;
	$i=0;
	mysqli_query($db_conn, "DELETE FROM ".$_CONFIG['t_attr']." WHERE id = ".$uid." AND type = 'r'");
	if(mysqli_affected_rows($db_conn) == 1) {$i++;}
	foreach($role as $new_role){
		mysqli_query($db_conn, "INSERT INTO ".$_CONFIG['t_attr']." (`id`, `type`, `value`) VALUES (
		'".mysqli_real_escape_string($db_conn, $uid)."',
		'r',
		'".mysqli_real_escape_string($db_conn, $new_role)."')");
		if(mysqli_affected_rows($db_conn) == 1) {$i++;}
	}
	if($i > 0){
		return true;
	}else{
		return false;//non è detto sia incorso un errore (l'utente potrebbe avere 0 ruoli)
	}
}

function license_del($id, $dimension){
	global $_CONFIG, $db_conn;

	if($dimension == "groups"){
		$verb = _("is in");
	}elseif($dimension == "roles"){
		$verb = _("has this");
	}
	
	$single = _(substr($dimension, 0, -1));
	
	$table = $_CONFIG['table_prefix']."user_".$dimension;
	$result = mysqli_query($db_conn, "SELECT FROM `fad_user_attr` WHERE value = ".$id);
	print_r($result);
	$user_count = mysqli_num_rows($result);
	if($user_count == 0) {
		
		mysqli_query($db_conn, "DELETE FROM ".$table." WHERE id = ".$id." AND id != '1' LIMIT 1");

		if(mysqli_affected_rows($db_conn) == 1){
			return '<div class="text-center alert alert-success" role="alert">'.ucfirst($single)." "._("deleted successfully").'</div>';
		}else{
			return '<div class="text-center alert alert-danger" role="alert">'._("Error:")." "._("This")." ".$single." "._(" can't be deleted!").'</div>';
		}
	}else{
			return '<div class="text-center alert alert-danger" role="alert">'._("Error:")." ".$user_count." "._('user/s')." ".$verb." ".$single." "._(" I can not delete!").'</div>';
	}
}

function license_add($name, $dimension){
	global $_CONFIG, $db_conn;
	$single = substr($dimension, 0, -1);
	$table = $_CONFIG['table_prefix']."user_".$dimension;
	//echo "INSERT INTO ".$table." ('name') VALUES ('".mysqli_real_escape_string($db_conn, $name)."')";
	//echo "INSERT INTO `".$table."` (`name`) VALUES ('".mysqli_real_escape_string($db_conn, $name)."')";
	mysqli_query($db_conn, "INSERT INTO ".$table." (`name`) VALUES ('".mysqli_real_escape_string($db_conn, $name)."')");
	if(mysqli_affected_rows($db_conn) == 1){
		return '<div class="text-center alert alert-success" role="alert">'._("New")." ".$single." "._("created successfully").'</div>';
	}else{
		return '<div class="text-center alert alert-danger" role="alert">'._("Error:")." "._("New")." ".$single." "._(" can't be created!").'</div>';
	}
}