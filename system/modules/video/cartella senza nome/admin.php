<?php if(!isset($status)){auth_check_point();}
$video = true; ?>
<div class="col-xs-12">
<?php
include('db.php');
session_start();
$session_id='1'; //$session id
$path = "uploads/";

	$valid_formats = array("mp4");
	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
		{
			$name = $_FILES['photoimg']['name'];
			$size = $_FILES['photoimg']['size'];
			
			if(strlen($name))
				{
					list($txt, $ext) = explode(".", $name);
					if(in_array($ext,$valid_formats))
					{
					if($size<(1024*10024))
						{
							$actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
							$tmp = $_FILES['photoimg']['tmp_name'];
							if(move_uploaded_file($tmp, $path.$actual_image_name))
								{
?>
   <div class="no-brand flowplayer" data-swf="flowplayer.swf" data-ratio="0.4167">
      <video>
         <!--<source type="video/webm" src="https://stream.flowplayer.org/bauhaus.webm">-->
         <source type="video/mp4" src="<?php echo $actual_image_name; ?>">
      </video>
   </div>
									
			<?php
								}
							else
								echo "failed";
						}
						else
						echo "Image file size max 1 MB";					
						}
						else
						echo "Invalid file format..";	
				}
				
			else
				echo "Please select image..!";
				
			exit;
		}
?>

</div>
