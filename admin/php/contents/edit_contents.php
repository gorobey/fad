<?php
require_once( "../../../config.php");
require_once( "../../../system/includes/auth.lib.php");
require_once( "../../../system/includes/license.lib.php");
require_once("../../../system/includes/utils.lib.php");
list($status, $user) = auth_get_status();
if($status !== AUTH_LOGGED){ die(); }
$user_id = $user['id'];
print_r($_POST);
echo "<br />";
print_r($_SESSION);
if(isset($_POST['action']) && $_POST['action']!=""){
	if($_POST['level'] < 2){
		if($_POST['action'] == "n"){
			$affected = mysqli_query($db_conn, "INSERT INTO ".$_CONFIG['t_taxonomy']." (type) VALUES ('".mysqli_real_escape_string($db_conn, $_POPT['filter'])."'");
			if(mysqli_num_rows($affected)==1){
			//$affected = mysqli_query($db_conn, "INSERT INTO ".$_CONFIG['t_tlocale']." (type) VALUES ('".mysqli_real_escape_string($db_conn, $_POPT['filter'])."'");				
			}
	
		}elseif($_POST['action'] == "e"){
			$query = "UPDATE";
		}elseif($_POST['action'] == "d"){//vedere come spostare contenuti in nuova taxonomy //la query va sistemata è solo un esempio rubato su stackoverflow
			$query = "DELETE s.* FROM spawnlist s
						INNER JOIN npc n ON s.npc_templateid = n.idTemplate
						WHERE (n.type = 'monster')";
		}
		
	}elseif($_POST['level'] >= 2){
		if($_POST['action'] == "n"){
			$query = "INSERT INTO ".$_CONFIG['t_item'];
			$query = "INSERT INTO ".$_CONFIG['t_module'];
		}elseif($_POST['action'] == "e"){
			$query = "UPDATE ".$_CONFIG['t_item'];	
			$query = "UPDATE ".$_CONFIG['t_module'];
		}elseif($_POST['action'] == "d"){//la query va sistemata è solo un esempio rubato su stackoverflow
			$query = "DELETE s.* FROM spawnlist s
						INNER JOIN npc n ON s.npc_templateid = n.idTemplate
						WHERE (n.type = 'monster')";
		}
	}
	exit;
}else{
?>
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
					<?php echo render_page('admin', $_GET['type'], $user_id); ?>
					<input type="hidden" name="action" value="<?php echo $_GET['action'];?>" />
					<input type="hidden" name="lang" value="<?php echo $_SESSION['locale'];?>" />
	        </div>
	    </div>
	</form>
</div><!-- /. ROW  -->
	<?php
}
?>