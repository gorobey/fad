<!-- menu vertical -->
<div class="col-md-3">
	<div class="panel navigation">
		<nav id="menu" class="left">
			
		<?php
		$user_id = auth_check_point();
		 if($user_id!=-1){	?>
		<div class="text-center img_sid">
			<?php echo profile_img($user_id, "70");?>
				<div id="photo_profile_load"></div>
				<form enctype="multipart/form-data" name="upload" id="upload" method="post" action="../user/php/upload.php">
						<input type="file" name="image" id="editp" />
				</form>
				<div id="up_img_p"><span class="fa fa-camera-retro fa-2x"></span></div>
		</div>
		<?php }else{ ?>
		<hr />
		<p class="text-center"><?php echo _('You are not logged in');?><span> &bull; <?php login_out($path);?></span>
		<?php } ?>
		<hr />
		<div class="sidebar-scroll">
			<?php 
			$menu_arr = json_decode(get_info('nav-'.$_SESSION['locale']), true);
			function MakeMenu($Array){
				$Output = '<ul>';
				foreach($Array as $Key => $Value){
					$user_id = auth_check_point();
					echo link_filter($user_id, "frontend", 2, $Value['link']);
					if(link_filter($user_id, "frontend", 2, $Value['link']) == 1){
						$Output .= "<li class='".$Value['classname']."'><a href='".protocol().str_replace("//", "/", get_info('appdir').$Value['link'])."'>".$Value['title'];
						if(isset($Value['children'])){
							$Output .= '<i class="fa fa-plus-circle"></i>';
						}
						$Output .= "</a>";
						if(isset($Value['children'])){
							$Output .= MakeMenu($Value['children']);
						}
						$Output .= '</li>';
					}
				}
				echo $Output;
			}
			echo MakeMenu($menu_arr);
			echo lang_menu(true);
			echo '</ul>';
			?>
		</div>
			<a href="#" id="showmenu" class="visible-xs visible-sm"><i class="fa fa-bars"></i></a>
		</nav>
	</div>
</div>
