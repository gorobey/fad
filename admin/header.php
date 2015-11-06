<!DOCTYPE html>
<html lang="<?php echo substr($_SESSION['locale'], 0, 2); ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0"/>
	<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<meta http-equiv="robots" content="noindex,nofollow" />
	<meta name="author" Content="Giulio Gorobey">
	<title><?php echo get_info('title')." | ".get_info('description'); ?></title>
	<link rel="shortcut icon" href="../system/style/imgs/favicon.png" />
	<link href="../system/style/css/bootstrap.min.css" rel="stylesheet" />
	<link href="../system/style/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="../system/style/css/dataTables.bootstrap.css" rel="stylesheet" />
	<link href="../system/style/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../system/style/css/chartist.min.css">
	<link href="../system/style/css/custom.css" rel="stylesheet" />
    <script src="../system/js/jquery.min.js"></script>
    <script src="../system/js/jquery.dataTables.min.js"></script>
    <script src="../system/js/dataTables.bootstrap.js"></script>
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <span class="navbar-brand"><?php echo get_info('title'); ?></span>
                <?php login_out("../"); ?>
            </div>
        </nav>   
           <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav" id="main-menu">
					<li class="text-center">
                    <?php echo profile_img($user_id, "clear");?>
                    	<div id="photo_profile_load"></div>
						<form enctype="multipart/form-data" name="upload" id="upload" method="post" action="../user/php/upload.php">
					    		<input type="file" name="image" id="editp" />
						</form>
						<div id="up_img_p"><span class="fa fa-camera-retro fa-2x"></span></div>
					</li>
		            <li>
		                <a href="../"><i class="fa fa-home fa-2x"></i> <?php echo _('Home Page');?></a>
		            </li>
		            <li>
		                <a class="ajax" href="php/dashboard.php"><i class="fa fa-dashboard fa-2x"></i> <?php echo _('Dashboard');?></a>
		            </li>
					<?php if(is_admin($user_id)===true){ ?>
		            <li>
		                <a class="ajax" href="php/navigation.php"><i class="fa fa-compass fa-2x"></i> <?php echo _('Navigation');?></a>
		            </li>
		            <?php } ?>
		            <li>
		                <a href="#"><i class="fa fa-users fa-2x"></i> <?php echo _('Users');?><span class="fa expand-menu fa-minus-circle"></span></a>
		                <ul class="nav nav-second-level">
							<li>
								<a class="ajax fa fa-caret-right" href="php/users.php"> <?php echo _('Users');?></a>
							</li>
				            <li>
				                <a class="ajax fa fa-caret-right" href="php/attrs.php?a=groups"> <?php echo _('Groups');?></a>
				            </li>
				            <li>
				                <a class="ajax fa fa-caret-right" href="php/attrs.php?a=roles"> <?php echo _('Roles');?></a>
				            </li>
						</ul>
					</li>
					<?php menu('admin', $user_id); ?>
					<li>
						<a href="#"><i class="fa fa-language fa-2x"></i> <?php echo _('Language');?><span class="fa expand-menu fa-minus-circle"></span></a>
						<ul class="nav nav-second-level">
							<?php lang_menu(true); ?>
						</ul>
					</li>
					<li>
					<a class="ajax" href="php/options.php"><i class="fa fa-cogs fa-2x"></i><?php echo _('Options');?></a>
					</li>
			      </ul>
			        <?php if(is_admin($user_id)===true){ echo "<span class='version'>V ".get_info('version')."</span>";} ?>
			    </div>
			</nav>
			<!-- /. NAV SIDE  -->
			<div id="page-wrapper">
				<div id="page-inner" class="load-here">
