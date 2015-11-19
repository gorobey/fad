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
    <link href="<?php echo $path; ?>frontend/style/css/custom.css" rel="stylesheet" />
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
	</head>
	<body>
	<nav class="navbar navbar-static">
	    <div class="container">
	      <a class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
	        <span class="glyphicon glyphicon-chevron-down"></span>
	      </a>
	      <div class="nav-collapse collase">
			<?php
			$menu_arr = json_decode(get_info('nav-'.$_SESSION['locale']), true);
			function MakeMenu($Array){
				$Output = '<ul class="nav navbar-nav">';
				foreach($Array as $Key => $Value){			
					$Output .= "<li class='".$Value['classname']."'><a href='".$Value['link']."'>".$Value['title']."</a>";
					if(is_array($Value['children'])){
						$Output .= MakeMenu($Value['children']);
					}
					$Output .= '</li>';
				}
				$Output .= '</ul>';
				return $Output;
			}
			echo MakeMenu($menu_arr); ?>    
	      </div>
			<form class="form-inline pull-right">
			   <div class="input-group">
			     <input type="text" class="form-control" placeholder="Search">
			     <div class="input-group-btn">
				       <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
			     </div>
			  </div>
			</form>
	     		
	    </div>
	</nav><!-- /.navbar -->

<header class="masthead">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
			<img src="../../system/style/imgs/logo.png" />
			<h1>Entra nel mondo de L'Ippogrifo <sup>&reg;</sup>
          <p class="lead">PIATTAFORMA PER LA FORMAZIONE A DISTANZA</p></h1>

      </div>
    </div>
  </div>
</header>


		<?php lang_menu(true); ?>
				
		
