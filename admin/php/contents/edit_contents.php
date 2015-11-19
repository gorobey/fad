<?php
require_once( "../../../config.php");
require_once( "../../../system/includes/auth.lib.php");
require_once( "../../../system/includes/license.lib.php");
require_once("../../../system/includes/utils.lib.php");
if(!isset($status)){$user_id = auth_check_point();}

if(isset($_POST['level'])){$level = $_POST['level'];}elseif(isset($_GET['level'])){$level = $_GET['level'];}else{$level = null;}
if(!ctype_digit($level)){die();}
//print_r($_POST);
if($level == 1 && ($_POST['action']=="n" || $_POST['action']=="e" || $_POST['action']=="d")){
	if($_POST['action'] == "n" && in_array($_POST['subfilter'], arr_item('admin', $_POST['filter']))){
		$insert_taxonomy = mysqli_multi_query($db_conn,
		"INSERT INTO ".$_CONFIG['t_taxonomy']." (`type`, `subtype`) VALUES ('".mysqli_real_escape_string($db_conn, $_POST['filter'])."', '".mysqli_real_escape_string($db_conn, $_POST['subfilter'])."');
		INSERT INTO ".$_CONFIG['t_locale']." (`rel`, `level`, `link`, `lang`, `key`, `value`) VALUES (LAST_INSERT_ID(), '1', '".str_replace(" ", "-", mysqli_real_escape_string($db_conn, $_POST['filter']."/".$_POST['name']))."' ,'".$_POST['locale']."', 'taxonomy', '".mysqli_real_escape_string($db_conn, $_POST['name'])."')");
		
		
		echo "INSERT INTO ".$_CONFIG['t_taxonomy']." (`type`, `subtype`) VALUES ('".mysqli_real_escape_string($db_conn, $_POST['filter'])."', '".mysqli_real_escape_string($db_conn, $_POST['subfilter'])."');
		INSERT INTO ".$_CONFIG['t_locale']." (`rel`, `level`, `link`, `lang`, `key`, `value`) VALUES (LAST_INSERT_ID(), '1', '".str_replace(" ", "-", mysqli_real_escape_string($db_conn, $_POST['filter']."/".$_POST['name']))."' ,'".$_POST['locale']."', 'taxonomy', '".mysqli_real_escape_string($db_conn, $_POST['name'])."')";
		
		
		
		if($insert_taxonomy === true){
			echo '<div class="alert alert-success" role="alert">'._("New").' '._("filter created!").'</div>'; exit;
		}else{
			echo '<div class="text-center alert alert-danger" role="alert">'._("Error:")." "._("filter can't be creaded!").'</div>'; exit;
		}
	}elseif($_POST['action'] == "e"){
		echo '<div class="text-center alert alert-danger" role="alert">'._("Error:")." "._("Not yet implemented!").'</div>';
	}elseif($_POST['action'] == "d"){//vedere come spostare contenuti in nuova taxonomy //la query va sistemata
		$delete_taxonomy = mysqli_query($db_conn,
		"DELETE ".$_CONFIG['t_taxonomy'].", ".$_CONFIG['t_item'].", ".$_CONFIG['t_locale'].", ".$_CONFIG['t_analytics']."
		FROM ".$_CONFIG['t_taxonomy']."
		INNER JOIN ".$_CONFIG['t_locale']." ON ".$_CONFIG['t_taxonomy'].".id = ".$_CONFIG['t_locale'].".rel
		INNER JOIN ".$_CONFIG['t_item']." ON ".$_CONFIG['t_taxonomy'].".id = ".$_CONFIG['t_item'].".rel
		INNER JOIN ".$_CONFIG['t_analytics']." ON ".$_CONFIG['t_taxonomy'].".id = ".$_CONFIG['t_analytics'].".content
		WHERE ".$_CONFIG['t_taxonomy'].".id = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'
		AND ".$_CONFIG['t_item'].".rel = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'
		AND ".$_CONFIG['t_locale'].".rel = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'");
	}	
}elseif($level == 2 && ($_POST['action']=="a" || $_POST['action']=="d" || $_POST['action']=="o")){
	
	if($_POST['action']=="a"){
		
		$dtime = DateTime::createFromFormat("d/m/Y H:i:s", $_POST['date']);
		$timestamp = $dtime->getTimestamp();
		$new_date = date('Y-m-d H:i:s', $timestamp);

		
		
		mysqli_query($db_conn, "INSERT INTO ".$_CONFIG['t_item']." (`rel`, `author`, `publish`, `date`) VALUES ('".mysqli_real_escape_string($db_conn, $_POST['rel'])."', '".mysqli_real_escape_string($db_conn,$user_id)."', '".TRUE."', '".$new_date."')");
		
	echo "INSERT INTO ".$_CONFIG['t_item']." (`rel`, `author`, `publish`, `date`) VALUES ('".mysqli_real_escape_string($db_conn, $_POST['rel'])."', '".mysqli_real_escape_string($db_conn,$user_id)."', '".TRUE."', '".$new_date."')";	
		
		
	}
	if($_POST['action'] == "a" || $_POST['action'] == "o"){
		$item_part = "";	
		foreach($_POST['content'] as  $key=>$value){
			$path_key = str_replace(" ", "-", $key."/");
			$item_part .= "INSERT INTO `".$_CONFIG['t_locale']."` (`rel`, `level`, `link`, `lang`, `key`, `value`) VALUES ('".$_POST['rel']."', '2', '".mysqli_real_escape_string($db_conn, $_POST['link_content']).$path_key."', '".$_SESSION['locale']."', '".$key."', '".$value."') ON DUPLICATE KEY UPDATE `key` = '".$key."', `link` = '".mysqli_real_escape_string($db_conn, $_POST['link_content']).$path_key."', `value` = '".$value."'; ";
		}
		$insert_content = mysqli_multi_query($db_conn, $item_part);
		if($insert_content === true){
			echo '<div class="alert alert-success" role="alert">'._("Content edited").'</div>'; exit;
		}else{
			echo '<div class="text-center alert alert-danger" role="alert">'._("Error:")." "._("content not created/edited").'</div>'; exit;
		}
	}elseif($_POST['action'] == "d"){
		$delete_item = mysqli_query($db_conn,
		"DELETE ".$_CONFIG['t_taxonomy'].", ".$_CONFIG['t_item'].", ".$_CONFIG['t_locale'].", ".$_CONFIG['t_analytics']."
		FROM ".$_CONFIG['t_taxonomy']."
		INNER JOIN ".$_CONFIG['t_locale']." ON ".$_CONFIG['t_taxonomy'].".id = ".$_CONFIG['t_locale'].".rel
		INNER JOIN ".$_CONFIG['t_item']." ON ".$_CONFIG['t_taxonomy'].".id = ".$_CONFIG['t_item'].".rel
		INNER JOIN ".$_CONFIG['t_analytics']." ON ".$_CONFIG['t_taxonomy'].".id = ".$_CONFIG['t_analytics'].".content
		WHERE ".$_CONFIG['t_taxonomy'].".id = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'
		AND ".$_CONFIG['t_item'].".rel = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'
		AND ".$_CONFIG['t_locale'].".rel = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'");
		if($delete_item === true){
			echo '<div class="alert alert-success" role="alert">'._("Content deleted!").'</div>'; exit;
		}else{
			echo '<div class="text-center alert alert-danger" role="alert">'._("Error:")." "._("content can't be deleted!").'</div>'; exit;
		}
	}
}
if($_GET['action'] == 'a' || $_GET['action'] == 'o') { ?>
<div class="row">
	<div class="col-md-12" id="dashboard">
	     <h2><?php echo ucfirst($_GET['subtype']); ?></h2>
	     <div class="comfirm-box fa fa-times"></div>
		 <hr />
	</div>
	<form class="new_content col-md-12 col-sm-12 col-xs-12" id="new_content" method="POST" action="php/contents/edit_contents.php">               
	    <div class="panel panel-default">
	        <div class="panel-heading">
	            <?php echo _('Publisher'); ?>
	            <span class="pull-right">
					<label class="checkbox-inline">
						<input class="checkbox" type="checkbox" id="email-it" value="1" /><?php echo _('send mail to users'); ?>
					</label>
					<?php if($_GET['action']=="a"){ ?>
						<button type="submit" class="btn btn-primary btn-xs"><?php echo _('Publish Now');?></button>
						<?php }
						elseif($_GET['action']=="o"){ ?>
						<button type="submit" class="btn btn-primary btn-xs"><?php echo _('Update');?></button>
					<?php } ?>
				</span>
	        </div>
	        <div class="panel-body">
				<div class="col-xs-12">
					<ul class="nav nav-tabs">
						<?php lang_menu("tab"); ?>
					</ul>
					<br />
				</div>
				<?php echo render_editor('admin', $_GET['level'], $_GET['type'], $_GET['subtype'], $user_id, $_GET['id']); ?>
				<input type="hidden" name="action" value="<?php echo $_GET['action']; ?>" />
				<input type="hidden" name="type" value="<?php echo $_GET['type']; ?>" />
				<input type="hidden" name="subtype" value="<?php echo $_GET['subtype']; ?>" />
				<input type="hidden" name="link_content" value="<?php echo $_GET['link_content']; ?>" />
				<input type="hidden" name="rel" value="<?php echo intval($_GET['id']);?>" />			
				<input type="hidden" name="level" value="2" />
				<input type="hidden" name="lang" value="<?php echo $_SESSION['locale'];?>" />
	        </div>
	    </div>
	</form>
</div>
<?php require('../admin_scripts.php');
}
