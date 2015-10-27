<?php
function reg_register($data, $first=false){
	global $_CONFIG, $db_conn;
	$id = reg_get_unique_id();
	$clean_pass = gen_rand_string();
	$password = password_hash($clean_pass, PASSWORD_DEFAULT);
	if($first === true){
		$actived = 0;
	}else{
		$actived = 1;
		if(mail_exists($data['mail'])>0){
				return REG_FAILED;
			}
	}
	mysqli_query($db_conn, "
	INSERT INTO ".$_CONFIG['t_users']."
	(name, surname, password, mail, temp, regdate, uid)
	VALUES
	('".mysqli_real_escape_string($db_conn, $data['name'])."',
	'".mysqli_real_escape_string($db_conn, $data['surname'])."',
	'".$password."',
	'".mysqli_real_escape_string($db_conn, $data['mail'])."',
	'".$actived."',
	'".mysqli_real_escape_string($db_conn, time())."',
	'".mysqli_real_escape_string($db_conn, $id)."')
	");

	if(mysqli_insert_id($db_conn)){
		if($first != true){
			mysqli_query($db_conn, "
			INSERT INTO ".$_CONFIG['t_attr']." (`id`, `type`, `value`)
			VALUES (
			'".mysqli_real_escape_string($db_conn, mysqli_insert_id($db_conn))."',
			'g',
			'".mysqli_real_escape_string($db_conn, $data['group'])."'
			)");
			return reg_send_confirmation_mail($data['name'], $data['surname'], $data['mail'], $clean_pass, $data['mail'], $id);
		}else{
			return reg_admin_send_mail($data['name'], $data['surname'], $data['mail'], $clean_pass, $data['mail'], $id);
		}
	}else{
		return REG_FAILED;
	}
}

function reg_send_confirmation_mail($name, $surname, $mail, $password, $to, $id){
	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$server_name = str_replace("www.", "", $_SERVER['SERVER_NAME']);
	// Additional headers
	$headers .= 'To: '.$name.' <'.$mail.'>' . "\r\n";
	$headers .= 'From: '.get_info('title').' <admin@'.$server_name.'>';
	$to = $name.' '.$surname.' <'.$mail.'>';
	$subject = get_info('title').": data access for " . $name . " " .$surname;
	$message = "
	<html>
		<head>
			<title>".$subject."</title>
		</head>
		<body>
			<p>Hi, " . $name . "!<br />
			These are the access data:<br />
			Username: " . $mail . "<br />
			Password: " . $password . "<br />
			This is the link for activate your account:<br />
			<a href='http://".get_info('appdir')."login/confirm.php?id=".$id."'>http://".get_info('appdir')."login/confirm.php?id=".$id."</a><br />
			Do not forget to change password from your panel profile!</p>
		</body>
	</html>";

	// Mail it
	if(@mail($to, $subject, $message, $headers)){
	 	return REG_SUCCESS;
	}else{
		return REG_FAILED;
	}
}


function reg_admin_send_mail($name, $surname, $mail, $password, $to, $id){
	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$server_name = str_replace("www.", "", $_SERVER['SERVER_NAME']);
	// Additional headers
	$headers .= 'To: '.$name.' <'.$mail.'>' . "\r\n";
	
	$headers .= 'From: '.get_info('title').' <admin@'.$server_name.'>';
	$to = $name.' '.$surname.' <'.$mail.'>';
	$subject = get_info('title').": data access for " . $name . " " .$surname;
	$message = "
	<html>
		<head>
			<title>".$subject."</title>
		</head>
		<body>
			<p>Hi, " . ucfirst($name) . "!<br />
			These are the access data:<br />
			Username: " . $mail . "<br />
			Password: " . $password . "<br />
			Do not forget to change password from your panel profile!</p>
		</body>
	</html>";

	// Mail it
	if(@mail($to, $subject, $message, $headers)){
	 	return REG_SUCCESS;
	}else{
		return REG_FAILED;
	}
	return $password;
}

function send_edit_link($mail) {
	if(mail_exists($mail)===1){ //if(isValidEmail($mail)){
		global $_CONFIG, $db_conn;
		$rand = reg_get_unique_id();
		//list($user, $domain) = explode("@", $mail);
		if(mysqli_query($db_conn, "
		UPDATE ".$_CONFIG['t_users']."
		SET uid='".$rand."' WHERE mail='".$mail."'
		")) {
			$user_info = user_from_email($mail);
			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$server_name = str_replace("www.", "", $_SERVER['SERVER_NAME']);
			// Additional headers
			$headers .= 'To: '.$user_info['name'].' <'.$user_info['mail'].'>' . "\r\n";
	
			$headers .= 'From: '.get_info('title').' <admin@'.$server_name.'>';
			$to = $user_info['name'].' '.$user_info['surname'].' <'.$user_info['mail'].'>';
			$subject = get_info('title').": password recovery";
			$message = "
			<html>
				<head>
					<title>".$subject."</title>
				</head>
				<body>
					<p>Hi, " . ucfirst($user_info['name']) . "!<br />
					If you started the procedure for password recovery, go to this link:<br />
					<a href='http://".get_info('appdir')."login/?q=EDIT&uid=".$user_info['uid']."&mail=".$user_info['mail']."'>http://".get_info('appdir')."?q=EDIT&uid=".$user_info['uid']."&mail=".$user_info['mail']."</a><br />
					Otherwise, ignore this message<br />
					Do not forget to change password from your panel profile!</p>
				</body>
			</html>";
			// Mail it
			if(@mail($to, $subject, $message, $headers)){
			 	echo '<div class="alert alert-info" role="alert">'._("Go to your e-mail box for reset instruction!").'</div>';
			}else{
				echo '<div class="alert alert-danger" role="alert">'._("Error! Contact the admin!").'</div>';
			}
		}
	}else{
		echo '<div class="alert alert-warning" role="alert">'._("Error: Invalid address!").'</div>';
	}
}

function edit_passwd($uid, $mail){
	global $_CONFIG, $db_conn;
	
	//if (isset($_SERVER['HTTP_REFERER'])) {
	
	//	$uri = parse_url($_SERVER['HTTP_REFERER']);
	//	$domain = str_replace("www.", "", strtolower($uri['host']));
	//	Echo $domain;
	
	//}
	
	$rand = reg_get_unique_id();
	$clean_pass = gen_rand_string();
	$password = password_hash($clean_pass, PASSWORD_DEFAULT);
	mysqli_query($db_conn, "
	UPDATE ".$_CONFIG['t_users']."
	SET password='".$password."', uid='".$rand."' WHERE uid='".$uid."' AND mail = '".$mail."'
	");
	$affected = mysqli_query($db_conn, "
	SELECT uid
	FROM ".$_CONFIG['t_users']." WHERE uid='".$rand."' AND mail = '".$mail."' AND password='".$password."'");
	//echo mysqli_num_rows($affected);
	if(mysqli_num_rows($affected)==1){
		echo '<div class="alert alert-info" role="alert">'._("Ok, now your new password is").': <strong>"<span class="new_pass">'.$clean_pass.'</span>"</strong></div>';
	}else{
		echo '<div class="alert alert-warning" role="alert">'._("Error: password not reset!").'</div>';
	}
}

function reg_clean_expired(){
	global $_CONFIG, $db_conn;
	$query = mysqli_query($db_conn, "
	DELETE FROM ".$_CONFIG['t_users']."
	WHERE (regdate + ".($_CONFIG['regexpire'] * 60 * 60).") <= ".time()." and temp='1'");
}

function delete_user($user_edit){
	global $_CONFIG, $db_conn, $user_id;
	if(is_numeric($user_edit) && is_admin($user_id)) {
		if($user_edit!=1) {
			if(mysqli_query($db_conn, "
			DELETE FROM ".$_CONFIG['t_users']."
			WHERE id =". $user_edit) &&
			mysqli_query($db_conn, "
			DELETE FROM ".$_CONFIG['t_attr']."
			WHERE id =". $user_edit)) {
				echo '<div class="alert alert-success" role="alert">'._("User Delete!").'</div>';
			}else{
				echo '<div class="alert alert-danger" role="alert">'._("Error: Contact the admin!").'</div>';
			}
		}elseif($user_edit==1){
			echo '<div class="alert alert-warning" role="alert">'._("Error: Selected user can't be deleted!").'</div>';
		}else{
			echo '<div class="alert alert-danger" role="alert">'._("Error: Contact the admin!").'</div>';
		}
	}
}

function reg_get_unique_id(){
	//restituisce un ID univoco per gestire la registrazione
	list($usec, $sec) = explode(' ', microtime());
	mt_srand((float) $sec + ((float) $usec * 100000));
	return md5(uniqid(mt_srand((float) $sec + ((float) $usec * 100000)), true));
}

function reg_check_data($data){
	global $_CONFIG;	
	$errors = array();
	foreach($data as $field_name => $value){

		$func = $_CONFIG['check_table'][$field_name];
		if(!is_null($func)){
			$ret = $func($value);
			if($ret !== true)
				$errors[] = array($field_name, $ret);
		}
	}
	
	return count($errors) > 0 ? $errors : true;
}

function check_global($value){
	global $_CONFIG;
	
	$value = trim($value);
	if($value == "")
		return _("Error!");
	
	return true;
}

function reg_confirm($id){
	global $_CONFIG, $db_conn;
	$query = mysqli_query($db_conn, "
	UPDATE ".$_CONFIG['t_users']."
	SET temp='0', uid='".reg_get_unique_id()."' 
	WHERE uid='".$id."'");
	return (mysqli_affected_rows($db_conn) != 0) ? REG_SUCCESS : REG_FAILED;
}

function isValidEmail($mail){
	return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/", $mail);
}

function editEmail($mail, $userid){ 
	global $_CONFIG, $db_conn;
	if(mysqli_query($db_conn, "
	UPDATE ".$_CONFIG['t_users']."
	SET mail='".$mail."' WHERE id='".$userid."'
	")) {
		echo '<div id="notify" class="notify_notify">'._("E-Mail Edited!").'</div>';
	}else{
		echo '<div id="notify" class="notify_error">'._("Error: E-Mail Error!").'</div>';
	}
}

function editPass($pass, $userid){//errata
	global $_CONFIG, $db_conn;
	if(mysqli_query($db_conn, "
	UPDATE ".$_CONFIG['t_users']."
	SET password=MD5('".$pass."') WHERE id='".$userid."'
	")) {
		echo '<div id="notify" class="notify_notify">'._("Password Edited!").'</div>';
	}else{
		echo '<div id="notify" class="notify_error">'._("Error: Password Don't Match!").'</div>';
	}
}

function editmail_user($mail, $userid){ //deprecated
	global $_CONFIG, $db_conn;
	if(mysqli_query($db_conn, "
	UPDATE ".$_CONFIG['t_users']."
	SET mail='".$mail."' WHERE id='".$userid."'
	")) {
		echo '<div id="notify" class="notify_notify">'._("E-Mail Edited!").'</div>';
	}else{
		echo '<div id="notify" class="notify_error">'._("Error: E-Mail Error!").'</div>';
	}
}

function editpass_user($pass, $userid){ //errata
	global $_CONFIG, $db_conn;
	if(mysqli_query($db_conn, "
	UPDATE ".$_CONFIG['t_users']."
	SET password=MD5('".$pass."') WHERE id='".$userid."'
	")) {
		echo '<div id="notify" class="notify_notify">'._("Password Edited!").'</div>';
	}else{
		echo '<div id="notify" class="notify_error">'._("Error: Password Don't Match!").'</div>';
	}
}
