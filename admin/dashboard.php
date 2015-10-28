<?php
require_once( "../config.php");
require_once( "../system/includes/auth.lib.php");
require_once( "../system/includes/license.lib.php");
require_once("../system/includes/utils.lib.php");
if(!isset($status)){
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
	<div class="col-md-4 col-sm-12 col-xs-12">
	    <div class="panel panel-primary text-center no-boder bg-color-red">
	        <div class="panel-body">
	            <i class="fa fa-users fa-5x"></i>
	            <h3><?php echo get_users_count(); ?> <?php echo _("Users registred"); ?></h3>
	        </div>
	        <div class="panel-footer back-footer-red">
	            <a class="ajax" href="php/users.php"><?php echo _("Users registred"); ?></a>
	        </div>
	    </div>
	</div>
	<div class="col-md-4 col-sm-12 col-xs-12">
	    <div class="panel panel-primary text-center no-boder bg-color-red">
	        <div class="panel-body">
	            <i class="fa fa-wifi fa-5x"></i>
	            <h3><?php echo get_users_online()." "._("Active Users"); ?></h3>
	        </div>
	        <div class="panel-footer back-footer-red">
	            <a class="ajax" href="php/contents.php?type=course"><?php echo _("Users connected"); ?></a>
	        </div>
	    </div>
	</div>
	<div class="col-md-4 col-sm-12 col-xs-12">
	    <div class="panel panel-primary text-center no-boder bg-color-red">
	        <div class="panel-body">
	            <i class="fa fa-clock-o fa-5x"></i>
	            <h3>10000 hours</h3>
	        </div>
	        <div class="panel-footer back-footer-red">
	            <a class="ajax" href="php/contents.php?type=post"><?php echo _("Time connection"); ?></a>
	        </div>
	    </div>
	</div>
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="panel panel-default">
	        <div class="panel-heading"><?php echo _("Statistics"); ?></div>
	        <div class="panel-body">
				<?php require('php/analytics.php'); ?>
	            </div>
	        <div class="panel-footer back-footer">
	            <a class="ajax" href="#"><?php echo _("View statistics"); ?></a>
	        </div>
	    </div>
	</div>
</div>
