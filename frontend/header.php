<!DOCTYPE html>
<html lang="<?php echo substr($_SESSION['locale'], 0, 2); ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0"/>
	<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<meta http-equiv="robots" content="noindex,nofollow" />
	<meta name="author" Content="Giulio Gorobey">
    <title><?php echo get_info('title')." | ".get_info('description'); ?></title>
	<link rel="shortcut icon" href="<?php echo $path; ?>system/style/imgs/favicon.png" />
    <link href="<?php echo $path; ?>system/style/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo $path; ?>system/style/css/font-awesome.min.css" rel="stylesheet" />
    <link href="<?php echo $path; ?>frontend/style/css/jquery.scrollbar.css" rel="stylesheet" />
    <link href="<?php echo $path; ?>frontend/style/css/custom.css" rel="stylesheet" />
	<script src="<?php echo $path; ?>system/js/jquery.min.js"></script>
	</head>
	<body>
<!-- /menu vertical -->
<div class="container">
<header>
	<div class="row">
		<div class="col-md-9">
				<img class="pull-right hidden-xs hidden-sm" src="<?php echo $path; ?>frontend/style/imgs/ippogrifo.png">
				<img class="pull-right visible-xs visible-sm" src="<?php echo $path; ?>frontend/style/imgs/ippogrifo_small.png">
		</div>
		<div class="col-md-3"></div>
	</div>
</header>
