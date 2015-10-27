<?php
require_once( "../../config.php");
require_once( "../../system/includes/auth.lib.php");
require_once( "../../system/includes/license.lib.php");
require_once("../../system/includes/utils.lib.php");
require_once("../../system/includes/media.lib.php");
list($status, $user) = auth_get_status();
switch($status){
	case AUTH_NOT_LOGGED:
		exit;
	break;
	case AUTH_LOGGED:

	define("UPLOAD_DIR", "../profiles/");
	$user_id = $user['id'];
	$valid_formats = array("jpg", "png");
	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
		$name = $_FILES['image']['name'];
		$size = $_FILES['image']['size'];
		if(strlen($name)) {
				list($txt, $ext) = explode(".", $name);
				if(in_array($ext,$valid_formats)) {
					if($size<(1024*1024)) {
							$actual_image_name = md5(time().$txt).".".$ext;
							$tmp = $_FILES['image']['tmp_name'];
							if(move_uploaded_file($tmp, UPLOAD_DIR.$actual_image_name))
								{
								$old_img="../../user/profiles/".profile_image($user_id);
								if(profile_image($user_id)=="" && file_exists($old_img)){
									@unlink($old_img);
								}
								mysqli_query($db_conn ,"UPDATE ".$_CONFIG['t_users']." SET image='".$actual_image_name."' WHERE id='".$user_id."'");
								echo '<div class="text-center alert alert-success" role="alert">'._("Profile image Updated!").'</div>';
						} else {
							echo '<div class="text-center alert alert-warning" role="alert">'._("Error: Contact Admin").'</div>';
						}
					} else{
						echo '<div class="text-center alert alert-danger" role="alert">'._("Warning: Image file size max 1 MB").'</div>';
					}					
				} else {
					echo "<span id='notify' class='notify_error'>Warning: Invalid file format..</span>";
				}
		} else {
			echo "<span id='notify' class='notify_error'>Please select image..!</span>";
		}
		exit;
	}
}//switch($status)
?>
