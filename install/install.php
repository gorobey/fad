<?php
if($_POST['install'] == "install"){
	if($_POST['title'] == "" || $_POST['site_desc'] == "" || $_POST['name'] == "" || $_POST['surname'] == "" || $_POST['mail'] == ""){
		header('Location:index.php');
	}
?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<title>Platform Installer</title>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0"/>
				<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
				<meta http-equiv="robots" content="noindex,nofollow" />
				<meta name="author" Content="Giulio Gorobey">
				<meta name="Description" content="Platform: Install" />
				<link href="../system/style/css/bootstrap.min.css" rel="stylesheet" />
				<link href="../system/style/css/custom.css" rel="stylesheet" />
			</head>
		<body class="installer">
		<div class="container form_box">
			<div class="row">
				<div class="col-sx-12 text-center">
					<img src="../system/style/imgs/logo.png" />
					<h1>Entra nel mondo de L'Ippogrifo <sup>&reg;</sup></h1>
					<h2>PIATTAFORMA PER LA FORMAZIONE A DISTANZA</h2>
					<div id="install">
			<?php
			require_once("../config.php");
			require_once("../system/includes/utils.lib.php");
			require_once("../system/includes/reg.lib.php");

			$infos = array();
			$errors = array();
			$warnings = array();
			$cinfo = '<div class="text-center alert alert-info" role="alert">';
			$cwarning = '<div class="text-center alert alert-warning" role="alert">';
			$cerror = '<div class="text-center alert alert-danger" role="alert">';

			$tableCheck = mysqli_query($db_conn, "SHOW TABLES LIKE \"". $_CONFIG['table_prefix']."%\"");
			$tableExists = mysqli_num_rows($tableCheck);
			if($tableExists>0){
				array_push($warnings, "Intallation already performed!");
				$newname = '../.installed_'.rand();
				rename(dirname(__FILE__), $newname);
			}else{
				$sqlinstaller = 'database.sql';
				// Temporary variable, used to store current query
				$templine = '';
				// Read in entire file
				$lines = file($sqlinstaller);
				// Replace tables names
				$lines = str_replace($_CONFIG['info'], $_CONFIG['t_info'], $lines);
				$lines = str_replace($_CONFIG['users'], $_CONFIG['t_users'], $lines);
				$lines = str_replace($_CONFIG['attr'], $_CONFIG['t_attr'], $lines);
				$lines = str_replace($_CONFIG['roles'], $_CONFIG['t_roles'], $lines);
				$lines = str_replace($_CONFIG['groups'], $_CONFIG['t_groups'], $lines);
				$lines = str_replace($_CONFIG['login'], $_CONFIG['t_login'], $lines);
				$lines = str_replace($_CONFIG['session'], $_CONFIG['t_session'], $lines);
				$lines = str_replace($_CONFIG['analytics'], $_CONFIG['t_analytics'], $lines);
				$lines = str_replace($_CONFIG['taxonomy'], $_CONFIG['t_taxonomy'], $lines);
				$lines = str_replace($_CONFIG['item'], $_CONFIG['t_item'], $lines);
				$lines = str_replace($_CONFIG['locale'], $_CONFIG['t_locale'], $lines);
				// Loop through each line
				foreach ($lines as $line){
					// Skip it if it's a comment
					if (substr($line, 0, 2) == '--' || $line == ''){
					    continue;
					}
					// Add this line to the current segment
					$templine .= $line;
					// If it has a semicolon at the end, it's the end of the query
					if (substr(trim($line), -1, 1) == ';'){
						// Perform the query
						$lineinstall = mysqli_query($db_conn, $templine) or array_push($errors, 'Query error: ' . $templine);
						// Reset temp variable to empty
						$templine = '';
					}
				}
				
				if(count($errors)==0 && count($warnings)==0){
					array_push($infos, "Tables imported successfully!");
					$app_dir = str_replace("install/", "", ROOT_URL);
					$user_admin = array(
						'name' => $_POST['name'],
						'surname' => $_POST['surname'],
						'mail' => $_POST['mail'],
						'temp' => 0,
						'regdate' => '',
						'uid' => reg_get_unique_id(),
					);
					##gruppi base:
					mysqli_query($db_conn, "
						INSERT INTO ".$_CONFIG['t_groups']." (`name`)
						VALUES ('admin')
					");
					
					mysqli_query($db_conn, "
						INSERT INTO ".$_CONFIG['t_groups']." (`name`)
						VALUES ('subscriber')
					");
					##gruppo admin a user 1
					mysqli_query($db_conn, "
						INSERT INTO ".$_CONFIG['t_attr']." (`id`, `type`, `value`)
						VALUES ('1', 'g', '1')
					");

					mysqli_query($db_conn, "
						INSERT INTO ".$_CONFIG['t_info']." (`key`, `value`)
						VALUES ('title','".$_POST['title']."')
					");
				
					mysqli_query($db_conn, "
						INSERT INTO ".$_CONFIG['t_info']." (`key`, `value`)
						VALUES ('description','".$_POST['site_desc']."')
					");

					mysqli_query($db_conn, "
						INSERT INTO ".$_CONFIG['t_info']." (`key`, `value`)
						VALUES ('appdir','".$app_dir."')
					");
				
					mysqli_query($db_conn, "
						INSERT INTO ".$_CONFIG['t_info']." (`key`, `value`)
						VALUES ('version','".$_POST['version']."')
					");
				
					if(!file_exists("../uploads/")){
						if (!mkdir("../uploads/", 0700, true)) {
							array_push($errors, "upload folder not created");
						}else{
							array_push($infos, "upload folder created");
						}
					}
	
					reg_register($user_admin, true);
	
					if(count($warnings)==0 && count($errors)==0){
						array_push($infos, "Installation completed, data sent by email!");
						rename('../install', '../.installed_'.rand());
					}
			
				}		
			}
			
			if(count($errors)>0){
				$error_count = 0;
				foreach($errors as $error){
					$error_count++;
					if($error_count == 1){echo $cerror;}
					echo $error;
					if($error_count < count($errors)){
						echo "<hr />";
					}else{
						echo "</div>";
					}
					
				}
			}
			
			if(count($warnings)>0){
				$warning_count = 0;
				foreach($warnings as $warning){
					$warning_count++;
					if($warning_count == 1){echo $cwarning;}
					echo $warning;
					if($warning_count < count($warnings)){
						echo "<hr />";
					}else{
						echo "</div>";
					}
					
				}
			}

			if(count($infos)>0){
				$info_count = 0;
				foreach($infos as $info){
					$info_count++;
					if($info_count == 1){echo $cinfo;}
					echo $info;
					if($info_count < count($infos)){
						echo "<hr />";
					}else{
						echo "<hr />
						Go to <a href='http://".$app_dir."'>Login</a>!
						</div>";
					}
					
				}
			}
$to = date("Y"); ?>
<div class="text-center footer-form">
This Software is licensed under:<a href="http://it.wikipedia.org/wiki/GNU_General_Public_License" target="_blank">"GNU/GPL"</a> &bull; Copyright © <a rel="author" href="http://gorobey.it" target="_blank">Giulio Gorobey</a> 2014<?php if ($to != "2014"){echo "-".$to;} ?><br />
Please! Help to develop: <a href="mailto:gorobey@ippogrifogroup.com" target="_bank">Report Bugs</a> &bull; <a href="changelog.txt" target="_blank">V.<?php echo file_get_contents(ROOT.'/version.txt', true);?></a>
</div>
		</div>
	</body>
</html>
<?php
}else{
	header('Location:../../admin/');
}
