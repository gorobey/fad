<?php
$_AUTH = array("TRANSICTION METHOD" => AUTH_USE_COOKIE);

function login_out($path = "") {
	global $user_id;
	if($user_id!=-1){
		echo '<a href="'.$path.'login/logout.php" class="logout btn btn-danger square-btn-adjust">'._("Logout").'</a>';
	}else{
		echo '<a href="'.$path.'login/" class="logout btn btn-danger square-btn-adjust">'.("Login").'</a>';
	}
}

function auth_set_option($opt_name, $opt_value){
	global $_AUTH;
	$_AUTH[$opt_name] = $opt_value;
}

function auth_get_option($opt_name){
	global $_AUTH;
	return is_null($_AUTH[$opt_name]) ? NULL : $_AUTH[$opt_name];
}

function auth_clean_expired(){
	global $_CONFIG, $db_conn;
	$result = mysqli_query($db_conn, "SELECT UNIX_TIMESTAMP(creation_date) as creation_date FROM ".$_CONFIG['t_session']." WHERE uid='".auth_get_uid()."'");
	if($result){
		$data = mysqli_fetch_array($result);
		if($data['creation_date']){
			if($data['creation_date'] + $_CONFIG['expire'] <= time()){
				switch(auth_get_option("TRANSICTION METHOD")){
					case AUTH_USE_COOKIE:
						setcookie('uid',auth_get_uid(), -1, '/');//,auth_get_uid(), time('U')+$_CONFIG['expire'], '/' // time('U')+$_CONFIG['expire']
					break;
					case AUTH_USE_LINK:
						global $_GET;
						$_GET['uid'] = NULL;
					break;
				}
			}
		}
	}
	mysqli_query($db_conn, "DELETE FROM ".$_CONFIG['t_session']." WHERE UNIX_TIMESTAMP(creation_date) + ".$_CONFIG['expire']." <= ".time());
}

function auth_get_uid(){
	$uid = NULL;
	switch(auth_get_option("TRANSICTION METHOD")){
		case AUTH_USE_COOKIE:
			global $_COOKIE;
			if(isset($_COOKIE['uid'])){
				$uid = $_COOKIE['uid'];
			}
		break;
		case AUTH_USE_LINK:
			global $_GET;
			if(isset($_GET['uid'])){
				$uid = $_GET['uid'];
			}
		break;
	}
	return $uid ? $uid : NULL;
}

function auth_check_point(){
list($status, $user) = auth_get_status();
if($status !== AUTH_LOGGED){ die('<div class="text-center alert alert-danger" role="alert">'._("Error:")." "._("you can not stay here.").'
	<hr />
	<a href="'.protocol().get_info('appdir').'">'._('Please, login!').'</a>
	</div>'); }
	$user_id = $user['id'];
	return $user_id;
}

function auth_get_status(){
	global $_CONFIG, $db_conn;
	auth_clean_expired();
	$uid = auth_get_uid();
	if(is_null($uid)){
		return array(100, NULL);
	}
	$result = mysqli_query($db_conn, "SELECT U.id as id, U.name as name, U.surname as surname FROM ".$_CONFIG['t_session']." S,".$_CONFIG['t_users']." U WHERE S.user_id = U.id and S.uid = '".$uid."'");
	if(mysqli_num_rows($result) != 1){
		return array(100, NULL);
	}else{
		$user_data = mysqli_fetch_assoc($result);
		mysqli_query($db_conn, "UPDATE ".$_CONFIG['t_session']." SET uid='".$uid."', creation_date = NOW() WHERE user_id='".$user_data['id']."'");
		mysqli_query($db_conn, "UPDATE ".$_CONFIG['t_analytics']." SET `end_time` = NOW() WHERE uid = '".mysqli_real_escape_string($db_conn, $user_data['id'])."' AND end_time = 0");
		mysqli_query($db_conn, 'INSERT INTO '.$_CONFIG['t_analytics'].' (uid) VALUES ("'.mysqli_real_escape_string($db_conn, $user_data['id']).'")');
		setcookie('uid', $uid, time()+$_CONFIG['expire'], '/');
		return array(99, array_merge($user_data, array('uid' => $uid)));
	}
	if($status == AUTH_LOGGED && auth_get_option("TRANSICTION METHOD") == AUTH_USE_LINK){
		$link = "?uid=".$_GET['uid'];
	}else{
		$link = '';
	}
}

function auth_login($uname, $passwd){
	global $_CONFIG, $db_conn;
	$attempts = mysqli_query($db_conn, 'SELECT time FROM '.$_CONFIG['t_login'].' WHERE user = "'.mysqli_real_escape_string($db_conn, $uname).'" AND time > NOW() - INTERVAL 2 HOUR');
	if(mysqli_num_rows($attempts) < 3){
		$filter_user = mysqli_query($db_conn, "SELECT * FROM ".$_CONFIG['t_users']." WHERE mail='".$uname."'");
		if(mysqli_num_rows($filter_user) != 1){
			unset($filter_user);
			mysqli_query($db_conn, 'INSERT INTO '.$_CONFIG['t_login'].' (user) VALUES ("'.mysqli_real_escape_string($db_conn, $uname).'")');//bruteforce??
			return array(AUTH_INVALID_PARAMS, NULL);
		}else{
			$data_user = mysqli_fetch_array($filter_user);
			if(password_verify($passwd, $data_user['password'])){
				mysqli_query($db_conn, 'INSERT INTO '.$_CONFIG['t_analytics'].' (uid) VALUES ("'.mysqli_real_escape_string($db_conn, $data_user['id']).'")');
				return array(AUTH_LOGEDD_IN, $data_user);
			}else{
				mysqli_query($db_conn, 'INSERT INTO '.$_CONFIG['t_login'].' (user) VALUES ("'.mysqli_real_escape_string($db_conn, $uname).'")');//bruteforce??
				return array(AUTH_INVALID_PARAMS, NULL);
			}
		}
	}else{
		mysqli_query($db_conn, 'INSERT INTO "'.$_CONFIG['t_login'].'" (user) VALUES ("'.mysqli_real_escape_string($db_conn, $uname).'")');//bruteforce??
		return array(AUTH_BRUTE_FORCE, NULL);//bruteforce??
	}
}

function auth_logout(){
	global $_CONFIG, $db_conn;
	$uid = auth_get_uid();
	if(is_null($uid)){
		return false;
	}else{
		mysqli_query($db_conn, 'DELETE FROM '.$_CONFIG['t_session'].' WHERE uid = "'.$uid.'"');
		setcookie('uid', null, -1, '/');
		return true;
	}
}

function auth_generate_uid(){
	list($usec, $sec) = explode(' ', microtime());
	mt_srand((float) $sec + ((float) $usec * 100000));
	return md5(uniqid(mt_rand(), true));
}

function auth_register_session($udata){
	global $_CONFIG, $db_conn;
	if(mysqli_query($db_conn, 'DELETE FROM '.$_CONFIG['t_session'].' WHERE user_id = '.$udata['id'])){
		$uid = auth_generate_uid();
		mysqli_query($db_conn, 'INSERT INTO '.$_CONFIG['t_session'].' (uid, user_id)
		VALUES
		("'.mysqli_real_escape_string($db_conn, $uid).'", "'.mysqli_real_escape_string($db_conn, $udata['id']).'")');
		if(!mysqli_insert_id($db_conn)){
			return array(AUTH_LOGEDD_IN, $uid);
		}else{
			return array(AUTH_FAILED, NULL);
		}
	}else{
		return array(AUTH_FAILED, NULL);
	}
}
