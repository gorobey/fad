<!-- Begin Body -->
<div class="row">
	<?php require('navigation.php'); ?>
	<div class="col-md-6">
		<div class="panel load-here">
			<?php
			$install_dir = str_replace($_SERVER['HTTP_HOST'], "", ROOT_URL);
			$tree = explode("/", ltrim(str_replace($install_dir, "", rtrim($_SERVER['REQUEST_URI'], "/")), "/"));
			$link_content = str_replace($install_dir, "/",$_SERVER['REQUEST_URI']);
			render_page($user_id, 'frontend', 2, $link_content); ?>
			<hr />
		</div>
	</div>
	<?php require_once('sidebar.php'); ?>
</div>

