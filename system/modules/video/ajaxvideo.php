<?php 
require_once( "../../../config.php");
require_once( "../../../system/includes/auth.lib.php");
require_once( "../../../system/includes/license.lib.php");
require_once("../../../system/includes/utils.lib.php");
if(!isset($status)){auth_check_point();}
if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
	$name = $_FILES['videoinput']['name'];
	$size = $_FILES['videoinput']['size'];
	if(strlen($name)) {
		list($txt, $ext) = explode(".", $name);
		if(in_array($ext,$_CONFIG['video_ext'])){
			if($size<$_CONFIG['max_file_size']) {
				$actual_video_name = str_replace(" ", "_", time().substr(str_replace(" ", "_", $txt), 5).".".$ext);
				$tmp = $_FILES['videoinput']['tmp_name'];
				if(move_uploaded_file($tmp, "../../../uploads/".$actual_video_name)) { ?>
					<link rel="stylesheet" href="../system/modules/video/style/functional.css">
<script src="../system/js/jquery.min.js"></script>
					<script src="../system/modules/video/js/flowplayer.min.js"></script>
				   <div class="no-brand flowplayer" data-ratio="0.4167">
				      <video>
				         <source type="video/mp4" src="<?php echo protocol().get_info('appdir').'uploads/'.$actual_video_name;?>">
				      </video>
				   </div>
				   <script type="text/javascript">
						$(document).ready(function() {
							$("#filevideo").val("<?php echo protocol().get_info('appdir').'uploads/'.$actual_video_name;?>");
						});
					</script>
				<?php
				} else {
					echo _("Error: Contact the Admin!");
				}
			} else {
				echo _("Error: Image file size max")." ".formatBytes($_CONFIG['max_file_size'])."!";
			}
		} else {
			echo _("Error: Invalid file format!");
		}
	} else {
		echo _("Please select video!");
	}
	exit;
}
