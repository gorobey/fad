<?php
require_once( "../../../config.php");
require_once( "../../../system/includes/auth.lib.php");
require_once( "../../../system/includes/license.lib.php");
require_once("../../../system/includes/utils.lib.php");
list($status, $user) = auth_get_status();
if($status !== AUTH_LOGGED){ die(); }
$user_id = $user['id'];
if($_POST['action']=="n" || $_POST['action']=="e" || $_POST['action']=="d" && ctype_digit($_POST['level'])){
	if($_POST['level'] == 1){
		if($_POST['action'] == "n" && in_array($_POST['subfilter'], arr_item('admin', $_POST['filter']))){
			$insert_taxonomy = mysqli_multi_query($db_conn,
			"INSERT INTO ".$_CONFIG['t_taxonomy']." (`type`, `subtype`) VALUES ('".mysqli_real_escape_string($db_conn, $_POST['filter'])."', '".mysqli_real_escape_string($db_conn, $_POST['subfilter'])."');
			INSERT INTO ".$_CONFIG['t_locale']." (`rel`, `level`, `lang`, `key`, `value`) VALUES (LAST_INSERT_ID(), '1', '".$_POST['locale']."', 'taxonomy', '".$_POST['name']."')");
			if($insert_taxonomy === true){
				echo '<div class="alert alert-success" role="alert">'._("New").' '._("filter created!").'</div>';
			}else{
				echo '<div class="text-center alert alert-danger" role="alert">'._("Error:")." "._("filter can't be creaded!").'</div>';
			}
		}elseif($_POST['action'] == "e"){
			$query = "UPDATE";
		}elseif($_POST['action'] == "d"){//vedere come spostare contenuti in nuova taxonomy //la query va sistemata è solo un esempio rubato su stackoverflow
			$delte_taxonomy = mysqli_query(
			"DELETE ".$_CONFIG['t_taxonomy'].", ".$_CONFIG['t_item'].", ".$_CONFIG['t_locale']."
			FROM ".$_CONFIG['t_taxonomy']."
			INNER JOIN ".$_CONFIG['t_locale']." ON ".$_CONFIG['t_taxonomy'].".id = ".$_CONFIG['t_locale'].".rel
			INNER JOIN ".$_CONFIG['t_item']." ON ".$_CONFIG['t_taxonomy'].".id = ".$_CONFIG['t_item'].".rel
			WHERE ".$_CONFIG['t_taxonomy'].".id = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'
			AND ".$_CONFIG['t_item'].".rel = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'
			AND ".$_CONFIG['t_locale'].".rel = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'");
		}else{
				echo '<div class="text-center alert alert-danger" role="alert">'._("Error:")." "._("Contant the admin!").'</div>';
		}		
	}elseif($_POST['level'] == 2){
		if($_POST['action'] == "n"){
			$query = "INSERT INTO ".$_CONFIG['t_item'];
			$query = "INSERT INTO ".$_CONFIG['t_locale'];
		}elseif($_POST['action'] == "e"){
			$query = "UPDATE ".$_CONFIG['t_item'];	
			$query = "UPDATE ".$_CONFIG['t_locale'];
		}elseif($_POST['action'] == "d"){//la query va sistemata è solo un esempio rubato su stackoverflow
			$query = "DELETE s.* FROM spawnlist s
						INNER JOIN npc n ON s.npc_templateid = n.idTemplate
						WHERE (n.type = 'monster')";
		}
	}
}elseif($_GET['action'] == "a" && ctype_digit($_GET['level']) && isset($_GET['type'])){ ?>
<div class="row">
	<div class="col-md-12" id="dashboard">
	     <h2><?php echo ucfirst($_GET['type']); ?></h2>
	     <div class="comfirm-box fa fa-times"></div>
		 <hr />
	</div>
	<form class="new_content col-md-12 col-sm-12 col-xs-12" id="new_content" method="POST" action="php/contents/edit_contents.php">               
	    <div class="panel panel-default">
	        <div class="panel-heading">
	            <?php echo _('Publisher'); ?>
	            <button type="submit" class="right btn btn-primary btn-xs"><?php echo _('Pubblish Now');?></button>
	        </div>
	        <div class="panel-body">
			    <ul class="nav nav-tabs">
			    	<?php lang_menu("tab"); ?>
			    </ul>
			    <br />
					<?php echo render_page('admin', $_GET['type'], $_GET['subtype'], $user_id, $_GET['id']); ?>
					<input type="hidden" name="action" value="<?php echo $_GET['action'];?>" />
					<input type="hidden" name="lang" value="<?php echo $_SESSION['locale'];?>" />
	        </div>
	    </div>
	</form>
</div><!-- /. ROW  -->
	<?php
}else{
		echo "Error: contact the admin!";
}
?>
