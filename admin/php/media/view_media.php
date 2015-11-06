<?php
require_once( "../../../config.php");
require_once( "../../../system/includes/auth.lib.php");
require_once( "../../../system/includes/license.lib.php");
require_once("../../../system/includes/utils.lib.php");
require_once("../../../system/includes/media.lib.php");
if(!isset($status)){auth_check_point();}
$up_root = '../../../uploads';
if(isset($_POST['file']) && substr( $_POST['file'], 0, 7 ) === "uploads"){
	$file = "../../../".$_POST['file'];
}else{
	$file = $up_root;
}
$bind_path = strpos($file, $up_root);	

if(!file_exists($file)){
	$error = _('File or Directory Not Found'); //exit;
}

if($bind_path===false){
	$error = _("Forbidden"); //exit;
}

if ($_POST['do'] == 'delete') {
	if(!file_exists($file)){
		echo '<div class="text-center alert alert-warning" role="alert">'._("Error: Element can not be deleted!").'</div>';	
	}else{
		if(!is_dir($file)){
			if(@unlink($file)) echo '<div class="text-center alert alert-success" role="alert">'._("File deleted").'</div>';
		}elseif(is_dir_empty($file)){
			if(@rmdir($file)) echo '<div class="text-center alert alert-success" role="alert">'._("Folder deleted").'</div>';
		}else{
			echo '<div class="text-center alert alert-warning" role="alert">'._("Error: Element can not be deleted!").'</div>';
		}
	}	
} elseif ($_POST['do'] == 'mkdir') {
	chdir($file);
	if(@mkdir($_POST['name'])){
		echo '<div class="text-center alert alert-success" role="alert">'._("Folder writed on server").'</div>';			
	}else{
		echo '<div class="text-center alert alert-warning" role="alert">'._("Error: directory can't be created!").'</div>';
	}
exit;
} elseif ($_POST['do'] == 'upload') {
	$allowed = strtoupper(implode("|", $_CONFIG['extensions']));
	
	$fileName = $_FILES["file_data"]["name"];
	$fileTmpLoc = $_FILES["file_data"]["tmp_name"];
	$fileType = $_FILES["file_data"]["type"];
	$fileSize = $_FILES["file_data"]["size"];
	$fileErrorMsg = $_FILES["file_data"]["error"]; // 0 for false... and 1 for true
	$fileName = preg_replace('#[^a-z.0-9]#i', '', $fileName); 
	$kaboom = explode(".", $fileName);
	$fileExt = strtoupper(end($kaboom));
	// $fileName = time().rand().".".$fileExt;
	if (!$fileTmpLoc) {
		echo '<div class="text-center alert alert-warning" role="alert">'._("Error: Please browse for a file before clicking the upload button.").'</div>';
	} elseif($fileSize > $_CONFIG['max_file_size']) { // if file size is larger than 5 Megabytes
		echo '<div class="text-center alert alert-warning" role="alert">'._("Error: file was too big").'</div>';
		@unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
	} elseif (!preg_match("/.(".$allowed.")$/i", $fileName) ) {
		 echo '<div class="text-center alert alert-warning" role="alert">'._("Error: file type not allowed").'</div>';
		 @unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
	} elseif ($fileErrorMsg == 1) { // if file upload error key is equal to 1
		echo '<div class="text-center alert alert-warning" role="alert">'._("Error: An error occured while processing the file. Try again.").'</div>';
	}else{
		//echo $file;
		$moveResult = move_uploaded_file($_FILES['file_data']['tmp_name'], $file.'/'.$_FILES['file_data']['name']);
		// Check to make sure the move result is true before continuing
		if ($moveResult !== true) {
			echo '<div class="text-center alert alert-warning" role="alert">'._("Error: File not uploaded. Try again.").'</div>';
		}else{
			echo '<div class="text-center alert alert-success" role="alert">'._("File uploaded!").'</div>';
		}
	}
	exit;
}
if(!isset($_POST['do'])){ ?>

	<div class="modal-body">

	    <div class="panel panel-default">
	        <div class="panel-heading">		
		
		 <div class="comfirm-box">
			     <span class="fa fa-times"></span>
		     <div class="content-box-message"></div>
		 </div>

		<div id="file_drop_target" class="text-center">
			<?php
			$allowed_ext = ".".implode(",.", $_CONFIG['extensions']); ?>
			<?php echo _('Drag Files Here To Upload'); ?><br /><b><?php echo _('OR');?></b><input name="file_data" id="file_data" class="text-center" type="file" accept="<?php echo $allowed_ext;?>" multiple />
		</div>
		<div id="upload_progress"></div><hr />

		<span id="breadcrumb">
		<?php
			$crumbs = explode("/",str_replace("../../../","", $file));
			$sub_dirs = 0;
			$crumb_level = count($crumbs);
			foreach($crumbs as $crumb){
				$path = "";
				if($sub_dirs > 0){
					$path .= $crumbs[$sub_dirs-1]."/";
				}
				$path .= $crumb;
				if(($sub_dirs+1) == $crumb_level){ $current_dir = " current_dir";}else{$current_dir = "";}
				echo "<a class='clickmedia".$current_dir."' rel='".$path."' href='php/media/view_media.php'>".trim(str_replace(array(".php","_"),array("",""),$crumb).'')."</a> / ";
				$sub_dirs++;
			} ?>
		</span>

			<form class="right" action="?" method="post" id="mkdir" />
				<input id="dirname" type="text" name="dirname" value="" placeholder="<?php echo _('New Folder'); ?>" /><!--class="form-control input-sm"-->
				<input type="hidden" value="mkdir" />
				<input class="btn btn-primary btn-xs" id="dircreate" type="submit" value="<?php echo _('Create'); ?>" />
			</form>
        </div>
		     <table class="table table-striped table-bordered table-hover text-center media" id="MediaTable<?php echo "Inc";?>">
		        <thead>
		            <tr>
		                <th class="sort_disabled text-center"><span class="fa fa-trash"><i class="text-zero">x</i></span></th>
		                <th><?php echo _('Name');?></th>
		                <th class="text-center"><?php echo _('Size');?></th>
		                <th class="text-center"><?php echo _('Modified');?></th>
		                <th class="text-center"><?php echo _('Permissions');?></th>
		            </tr>
		        </thead>
		        <tbody id="list">
			<?php
			if (is_dir($file)) {
				$directory = $file;
				$result = array();
				$files = array_diff(scandir($directory), array('.','..','.htaccess'));
				$row_n = 1;
			    foreach($files as $entry) if($entry !== basename(__FILE__)) {
		    		$i = $directory . '/' . $entry;
			    	$stat = stat($i); ?>
		            <tr id="<?php echo "row-".$row_n;?>">
		            	<td><?php
		                	if((is_dir_empty($i) || !is_dir($i)) && is_writable($directory)){ ?>
			                <span class="sort_disabled delete fa fa-trash-o" data-toggle="confirmation" data-placement="right" data-href="?do=delete&file=<?php echo str_replace("../../../", "", $i); ?>">
		                	<?php } ?></td>
		                <td>
		                <?php if(is_dir($i)){ ?> 
			                <a class="clickmedia" rel="<?php echo str_replace("../../../", "", $i);?>" href="php/media/view_media.php"><?php echo $entry;?></a>                
		                <?php } else{
			                $file = explode(".", $entry);
			                $type = strtolower(end($file));
			                $images = array("jpg", "jpeg", "gif", "png");
		                ?>
			                <a href="<?php echo str_replace("../../../", protocol().get_info('appdir'), $i); ?>" target="_blank"
				               class="<?php if(isset($_GET['include']) && in_array($type, $images)){
					               echo "image-url";
								}else{
									echo "";	
								}?>"><?php echo $entry;?></a>
						<?php } ?>
		                </td>
		            	<td><?php echo formatBytes($stat['size']); ?></td>
		                <td><?php echo RelativeTime($stat['mtime']); ?></td>
		                <td><?php
		                	if(is_dir($i)){echo "d";}else{echo "-";}
			                if(is_readable($i)){echo "r-";}else{echo "-";}
			                if(is_writable($i)){echo "w-";}else{echo "-";}
			                if(is_executable($i)){echo "x";}else{echo "-";}
			                ?></td>
		            </tr>
					<?php
					$row_n++;
			    }
			} ?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
$(function() {
	var table;
	table = $('#MediaTableInc').DataTable({
		"aoColumnDefs": [{ "bSortable": false, "aTargets": [ 0 ] }],
		"drawCallback": function() {
        	draw_delete();
		}
	});

	var MAX_UPLOAD_SIZE = <?php echo $MAX_UPLOAD_SIZE; ?>;
	
	// file upload stuff
	$('#file_drop_target').on('dragover',function(){
		$(this).addClass('drag_over');
		return false;
	}).on('dragend',function(){
		$(this).removeClass('drag_over');
		return false;
	}).on('drop',function(e){
		e.preventDefault();
		var files = e.originalEvent.dataTransfer.files;
		$.each(files,function(k,file) {
			uploadFile(file);
		});
		$(this).removeClass('drag_over');
	});
	$('#file_data').on('change',function(e) {
		e.preventDefault();
		$.each(this.files,function(k,file) {
			uploadFile(file);
		});
	});

	function uploadFile(file) {
		var folder = $('.current_dir').attr('rel');
		if(file.size > MAX_UPLOAD_SIZE) {
			var $error_row = renderFileSizeErrorRow(file,folder);
			$('#upload_progress').append($error_row);
			return false;
		}
		
		var $row = renderFileUploadRow(file,folder);
		$('#upload_progress').append($row);
		var fd = new FormData();
		fd.append('file_data',file);
		fd.append('file',folder);
		//fd.append('xsrf',XSRF);
		fd.append('do','upload');
		var xhr = new XMLHttpRequest();
		xhr.open('POST', 'php/media/view_media.php', true);
		xhr.onload = function() {
			$(".content-box-message").html(this.responseText);
			$(".comfirm-box").slideDown('fast');
			//console.log(this.responseText);
			$row.remove();
			if($(".content-box-message div").hasClass('alert-success')){
				var count = $("tbody tr").length;
				var rowid = count+1;
				var rowNode = table
				.row.add( [ '<span data-href="?do=delete&amp;file='+folder+'/'+file.name+'" data-placement="right" data-toggle="confirmation" class="sort_disabled delete fa fa-trash-o" data-original-title="" title=""></span>',
				'<a href="<?php protocol().get_info('appdir')?>'+folder+'/'+file.name+'" class="image-url" target="_blank" >'+file.name+'</a>',
				formatFileSize(file.size),
				'<?php echo _("just now"); ?>',
				'-r-w--'] )
				.draw().node();
				$(rowNode).attr("id", "row-"+rowid);
				for(i=0;i<3;i++) {
					$(rowNode).fadeTo('slow', 0.5).fadeTo('slow', 1.0);
				}
			}
  		};
		xhr.upload.onprogress = function(e){
			if(e.lengthComputable) {
				$row.find('.progress-bar').css('width',(e.loaded/e.total*100 | 0)+'%' );
			}
		};
	    xhr.send(fd);
	}
	function renderFileUploadRow(file,folder) {
		return $row = $('<div/>')
			.append( $('<div class="progress"><div class="up_info text-center">'+file.name+' '+'('+formatFileSize(file.size)+')</div><div class="progress-bar progress-bar-success" role="progressbar"></div></div>'))
	};


	function formatFileSize(bytes) {
		var s = ['bytes', 'KB','MB','GB','TB','PB','EB'];
		for(var pos = 0;bytes >= 1000; pos++,bytes /= 1024);
		var d = Math.round(bytes*10);
		return pos ? [parseInt(d/10),".",d%10," ",s[pos]].join('') : bytes + ' bytes';
	}

	$(".image-url").on("click", function (e) {
		e.preventDefault();
		var image_url = $(this).attr("href");
		$(".bootstrap-wysihtml5-insert-image-url").val(image_url);
	});

	$('a.clickmedia').on('click', function(e) {
		e.preventDefault();
		var file = $(this).attr('rel');
		var load_here = $(".img-library");
		<?php
			$ajax_link = "php/media/view_media.php";
			 if(isset($_GET['include'])){
			$ajax_link .= "?include";
		} ?>
		$.ajax({
			dataType: "html",
			type: "POST",
			data: {file:file},
			url: "<?php echo $ajax_link;?>",
			dataType: "html",
				
			error: function(){		
				// Load the content in to the page.
				load_here.html("<p class='loading-error text-center'>Oops! Errore di caricamento!</p>");
			},
			
			beforeSend: function(){
				table.destroy();
	            load_here.empty();
				load_here.addClass('preload-content');
			},
					
			success: function (result) {
				load_here.removeClass('preload-content').html(result);
			}
		});
	});
	
	$("#dircreate").on("click", function (e) {
		e.preventDefault();
		var hashval = $('.current_dir').attr('rel');
		var dir = $("#dirname").val();
		if(dir !=""){
			$.ajax({
				type: "POST",
				url: "php/media/view_media.php",
				data: {'do':'mkdir',name:dir,file:hashval},
				dataType: "html",
				error: function(){		
					$(".content-box-message").html('<div class="alert alert-danger" role="alert"><?php echo _('Error: Contact system administrator!');?></div>');
					$(".comfirm-box").slideDown('fast');
				},					
				success: function (result) {
					$(".content-box-message").html(result);
					$(".comfirm-box").slideDown('fast');
					if($(".content-box-message div").hasClass('alert-success')){
						var count = $("tbody tr").length;
						var rowid = count+1;
						var rowNode = table
						.row.add( [ '<span class="sort_disabled delete fa fa-trash-o" data-toggle="confirmation" data-placement="right" data-href="?do=delete&file='+hashval+'/'+dir+'">',
						'<a class="clickmedia" rel="'+hashval+'/'+dir+'" href="php/media/view_media.php">'+dir+'</a>',
						"4096 KB",
						'<?php echo _("just now");?>',
						'dr-w-x'] )
						.draw().node();
						$(rowNode).attr("id", "row-"+rowid);
						for(i=0;i<3;i++) {
							$(rowNode).fadeTo('slow', 0.5).fadeTo('slow', 1.0);
						}
					}
				}
			});
			
		}
		return false;
	});
	
	function draw_delete(){
		$('.delete').confirmation({
			singleton: true,
			title: "<?php echo _('Are you sure you want to delete?')?>",
			popout: true,
			btnCancelLabel: "<?php echo _('Undo');?>",
			btnOkLabel: "<?php echo _('Ok!');?>",
			onConfirm: function (){
				var rowid = $(this).parent().parent().attr('id');
				var url = $(this).attr('data-href');
				var vars={} , hash="";
					var hashes = url.slice(url.indexOf('?') + 1).split('&');
					for(var i = 0; i < hashes.length; i++) {
					hash = hashes[i].split('=');
					vars[hash[0]] = hash[1];
				}
				$.ajax({
					type: "POST",
					url: "php/media/view_media.php",
					data: vars,
					dataType: "html",
					error: function(){		
						$(".content-box-message").html('<div class="alert alert-danger" role="alert"><?php echo _('Error: Contact system administrator!');?></div>');
						$(".comfirm-box").slideDown('fast');
					},					
					success: function (result) {
						$(".content-box-message").html(result);
						$(".comfirm-box").slideDown('fast');
						var rowNode =   table
						.row( $("#"+rowid).remove() )
						.remove()
						.draw();
					}
				});
			},
			onCancel: function (){
				$('.delete').confirmation('hide');
			}
		});
	}
	draw_delete();
	
	
	$('#ModalFile').on('hide.bs.modal', function () {//bug fix (http://datatables.net/manual/tech-notes/3)
	   table.destroy();
	});
});
</script>
<?php
}
unset($_GET['include']);
