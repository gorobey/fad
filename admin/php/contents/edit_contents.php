<?php
require_once( "../../../config.php");
require_once( "../../../system/includes/auth.lib.php");
require_once( "../../../system/includes/license.lib.php");
require_once("../../../system/includes/utils.lib.php");
if(!isset($status)){$user_id = auth_check_point();}
if(isset($_POST['level'])){
	$level = $_POST['level'];
}elseif(isset($_GET['level'])){
	$level = $_GET['level'];	
}else{
	$level = null;
}
if(!ctype_digit($level)){die();}
	if($_POST['level'] == 1 && ($_POST['action']=="n" || $_POST['action']=="e" || $_POST['action']=="d")){
		if($_POST['action'] == "n" && in_array($_POST['subfilter'], arr_item('admin', $_POST['filter']))){
			$insert_taxonomy = mysqli_multi_query($db_conn,
			"INSERT INTO ".$_CONFIG['t_taxonomy']." (`type`, `subtype`) VALUES ('".mysqli_real_escape_string($db_conn, $_POST['filter'])."', '".mysqli_real_escape_string($db_conn, $_POST['subfilter'])."');
			INSERT INTO ".$_CONFIG['t_locale']." (`rel`, `level`, `lang`, `key`, `value`) VALUES (LAST_INSERT_ID(), '1', '".$_POST['locale']."', 'taxonomy', '".$_POST['name']."')");
			if($insert_taxonomy === true){
				echo '<div class="alert alert-success" role="alert">'._("New").' '._("filter created!").'</div>';
				exit;
			}else{
				echo '<div class="text-center alert alert-danger" role="alert">'._("Error:")." "._("filter can't be creaded!").'</div>';
				exit;
			}
		}elseif($_POST['action'] == "e"){
			echo '<div class="text-center alert alert-danger" role="alert">'._("Error:")." "._("Not yet implemented!").'</div>';
		}elseif($_POST['action'] == "d"){//vedere come spostare contenuti in nuova taxonomy //la query va sistemata
			$delte_taxonomy = mysqli_query(
			"DELETE ".$_CONFIG['t_taxonomy'].", ".$_CONFIG['t_item'].", ".$_CONFIG['t_locale'].", ".$_CONFIG['t_analytics']."
			FROM ".$_CONFIG['t_taxonomy']."
			INNER JOIN ".$_CONFIG['t_locale']." ON ".$_CONFIG['t_taxonomy'].".id = ".$_CONFIG['t_locale'].".rel
			INNER JOIN ".$_CONFIG['t_item']." ON ".$_CONFIG['t_taxonomy'].".id = ".$_CONFIG['t_item'].".rel
			INNER JOIN ".$_CONFIG['t_analytics']." ON ".$_CONFIG['t_taxonomy'].".id = ".$_CONFIG['t_analytics'].".content
			WHERE ".$_CONFIG['t_taxonomy'].".id = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'
			AND ".$_CONFIG['t_item'].".rel = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'
			AND ".$_CONFIG['t_locale'].".rel = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'");
		}		
	}elseif($_POST['level'] == 2 && ($_POST['action']=="a" || $_POST['action']=="d" || $_POST['action']=="o")){
		if($_POST['action'] == "a"){
			$item_part = "";
			foreach($_POST['content'] as  $key=>$value){
				$item_part .= "INSERT INTO ".$_CONFIG['t_locale']." (`rel`, `level`, `lang`, `key`, `value`) VALUE ('".$_POST['rel']."', '2', '".$_SESSION['locale']."', '".$key."', '".$value."');";
			}
			$insert_item = mysqli_multi_query($db_conn,"INSERT INTO ".$_CONFIG['t_item']." (`rel`, `author`, `publish`) VALUES ('".$_POST['rel']."', '".$user_id."', '".TRUE."');".$item_part);
			if($insert_item === true){
				echo '<div class="alert alert-success" role="alert">'._("New").' '._("content published!").'</div>';
				exit;
			}else{
				echo '<div class="text-center alert alert-danger" role="alert">'._("Error:")." "._("content can't be published!").'</div>';
				exit;
			}
		}elseif($_POST['action'] == "o"){
			print_r($_POST);
			$query = "update ...";
		}elseif($_POST['action'] == "d"){
			echo 			"DELETE ".$_CONFIG['t_taxonomy'].", ".$_CONFIG['t_item'].", ".$_CONFIG['t_locale'].", ".$_CONFIG['t_analytics']."
			FROM ".$_CONFIG['t_taxonomy']."
			INNER JOIN ".$_CONFIG['t_locale']." ON ".$_CONFIG['t_taxonomy'].".id = ".$_CONFIG['t_locale'].".rel
			INNER JOIN ".$_CONFIG['t_item']." ON ".$_CONFIG['t_taxonomy'].".id = ".$_CONFIG['t_item'].".rel
			INNER JOIN ".$_CONFIG['t_analytics']." ON ".$_CONFIG['t_taxonomy'].".id = ".$_CONFIG['t_analytics'].".content
			WHERE ".$_CONFIG['t_taxonomy'].".id = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'
			AND ".$_CONFIG['t_item'].".rel = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'
			AND ".$_CONFIG['t_locale'].".rel = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'";
			$delete_item = mysqli_query(
			"DELETE ".$_CONFIG['t_taxonomy'].", ".$_CONFIG['t_item'].", ".$_CONFIG['t_locale'].", ".$_CONFIG['t_analytics']."
			FROM ".$_CONFIG['t_taxonomy']."
			INNER JOIN ".$_CONFIG['t_locale']." ON ".$_CONFIG['t_taxonomy'].".id = ".$_CONFIG['t_locale'].".rel
			INNER JOIN ".$_CONFIG['t_item']." ON ".$_CONFIG['t_taxonomy'].".id = ".$_CONFIG['t_item'].".rel
			INNER JOIN ".$_CONFIG['t_analytics']." ON ".$_CONFIG['t_taxonomy'].".id = ".$_CONFIG['t_analytics'].".content
			WHERE ".$_CONFIG['t_taxonomy'].".id = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'
			AND ".$_CONFIG['t_item'].".rel = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'
			AND ".$_CONFIG['t_locale'].".rel = '".mysqli_real_escape_string($db_conn, $_POST['id'])."'");
			if($delete_item === true){
				echo '<div class="alert alert-success" role="alert">'._("Content deleted!").'</div>';
				exit;
			}else{
				echo '<div class="text-center alert alert-danger" role="alert">'._("Error:")." "._("content can't be deleted!").'</div>';
				exit;
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
						<button type="submit" class="btn btn-primary btn-xs"><?php echo _('Pubblish Now');?></button>
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
				<input type="hidden" name="rel" value="<?php echo intval($_GET['id']);?>" />
				<input type="hidden" name="level" value="2" />
				<input type="hidden" name="lang" value="<?php echo $_SESSION['locale'];?>" />
	        </div>
	    </div>
	</form>
</div><!-- /. ROW  -->
<script type="text/javascript" src="../system/js/moment.min.js"></script>
<script type="text/javascript" src="../system/js/bootstrap-datetimepicker.min.js"></script>
<script>
$(function() {
	$('#datetimepicker').datetimepicker({
	    pickDate: true,                 //en/disables the date picker
	    pickTime: true,                 //en/disables the time picker
	    useMinutes: true,               //en/disables the minutes picker
	    useSeconds: true,               //en/disables the seconds picker
	    useCurrent: true,               //when true, picker will set the value to the current date/time
	    minuteStepping:1,               //set the minute stepping
	    minDate:"1/1/1900",               //set a minimum date
	  //  maxDate: ,     //set a maximum date (defaults to today +100 years)
	    language:'en',                  //sets language locale
	    defaultDate:"",                 //sets a default date, accepts js dates, strings and moment objects
	    disabledDates:[],               //an array of dates that cannot be selected
	    enabledDates:[],                //an array of dates that can be selected
	    useStrict: false,               //use "strict" when validating dates  
	    sideBySide: true,              //show the date and time picker side by side
	    daysOfWeekDisabled:[]          //for example use daysOfWeekDisabled: [0,6] to disable weekends
	});
});
</script>
<?php require('../admin_scripts.php');
}
