<?php
require_once( "../config.php");
require_once( "../system/includes/auth.lib.php");
require_once( "../system/includes/license.lib.php");
require_once("../system/includes/utils.lib.php");
list($status, $user) = auth_get_status();
switch($status){
	case AUTH_NOT_LOGGED:
		exit;
	break;
	case AUTH_LOGGED:
			//$view_user = $user['id'];
			$view_user = $_GET['id'];
			if(is_numeric($view_user)) {
			$user_info = user_get_info($view_user);
			$user_group = get_user_attr($view_user);
			$user_roles_arr = get_user_attr($view_user, "r");
			$roles_nums = count($user_roles_arr);
			$user_roles = "";
			$count = 0;
			if($roles_nums == 0){
				$user_roles .= _("No special roles");
			}else{
				foreach($user_roles_arr as $user_role){
					$user_roles .= $user_role;
					$count++;
					if($count > $roles_nums){$user_roles .= ",";}
				}
			}
			?>

<div class="row">
	<div class="col-md-12" id="dashboard">
	     <h2><?php echo is_online($view_user)." ".get_real_name($view_user);?></h2>
	     <div class="comfirm-box">
   		     <span class="fa fa-times"></span>
		     <div class="content-box-message">
		     </div>
	     </div>
		 <hr />
	</div>
	<div class="col-md-12 col-sm-12 col-xs-12">                     
	    <div class="panel panel-default">
	        <div class="panel-heading">
				<?php echo profile_img($view_user, "clear", "../"); ?>
			</div>
					<hr />
					<ul>
					<li><strong class="left"><?php echo _("Registered"); ?>:</strong><span class="right"><?php echo date("d/m/Y", $user_info['regdate']);?></span></li>
					<li><strong class="left"><?php echo _("E-mail"); ?>:</strong><span class="right"><?php echo $user_info['mail'];?></span></li>
					<li><strong class="left"><?php echo _("Group"); ?>:</strong><span class="right"><?php echo $user_group; ?></span></li>
					<li><strong class="left"><?php echo _("Roles"); ?>:</strong><span class="right"><?php  echo $user_roles; ?></span></li>
					</ul>
				</div>
			<?php
			}
	break;
} ?>
