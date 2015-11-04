<?php
if(!isset($status)){
	require_once( "../../config.php");
	require_once( "../../system/includes/auth.lib.php");
	require_once( "../../system/includes/license.lib.php");
	require_once("../../system/includes/utils.lib.php");
	list($status, $user) = auth_get_status();
	if($status !== AUTH_LOGGED){ die(); }
}
?>
<div class="row">
	<div class="col-md-12" id="dashboard">
	     <h2>Dashboard</h2>
	     <div class="comfirm-box">
   		     <span class="fa fa-times"></span>
		     <div class="content-box-message">
		     </div>
	     </div>
		 <hr />
	</div>
</div><!-- /. ROW  -->
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="panel panel-default">
	        <div class="panel-heading"><?php echo _("Statistics"); ?>
	        <span class="pull-right">
	        	<strong title="<?php echo _("Active Users"); ?>"><i class="fa fa-wifi"></i> <?php echo get_users_online(); ?></strong> /  
	        	<strong title="<?php echo _("Users Registred"); ?>"><i class="fa fa-users"></i> <?php echo get_users_count(); ?></strong>
	        </span></div>
	        <div class="panel-body">
				<?php require('analytics/graph.php'); ?>
				<hr />
				<?php require('analytics/list.php'); ?>
	        </div>
	    </div>
	</div>
</div>
