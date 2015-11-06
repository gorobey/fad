<?php
require_once( "../../config.php");
require_once( "../../system/includes/auth.lib.php");
require_once( "../../system/includes/license.lib.php");
require_once("../../system/includes/utils.lib.php");
if(!isset($status)){auth_check_point();} ?>
<div class="row">
	<div class="col-md-12" id="dashboard">
	     <h2><?php echo _('Navigation'); ?></h2>
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
	        <div class="panel-heading"><?php echo _("Navigation"); ?>
			</div>
	        <div class="panel-body">
				<div class="col-md-6 col-sm-6 col-xs-6">
					<ul><li></li></ul>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6">
					<ul><li></li></ul>
				</div>				
			</div>
	</div>
</div>
