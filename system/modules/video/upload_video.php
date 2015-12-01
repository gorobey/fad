<?php 
require_once( "../../../config.php");
require_once( "../../../system/includes/auth.lib.php");
require_once( "../../../system/includes/license.lib.php");
require_once("../../../system/includes/utils.lib.php");
if(!isset($status)){auth_check_point();} ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>



<script type="text/javascript">
 $(document).ready(function() { 


	$("#videoinput").on("change", function () {
		$("#preview").html('');
		$("#preview").html('<img src="../system/style/imgs/loading.gif" alt="Loading..."/><br />Loading...');
		$("#videoform").ajaxForm({
			success: function(data){
	            if(data != '') {
	                $("#preview").html(data);
	            }
	        }
		}).submit();
	});	

        }); 
</script>


<div class="text-center">

<form id="videoform" method="post" enctype="multipart/form-data" action='../system/modules/video/ajaxvideo.php'>
<span><?php echo _("Upload video").", "._("Max Size:").formatBytes($_CONFIG['max_file_size']);?></span><span><input type="file" name="videoinput" id="videoinput" accept="video/*" capture></span><span><i class="fa fa-video-camera"></i></span>
</form>
<div id='preview'></div>


</div>




	
</div>
