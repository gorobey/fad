<?php
$sublevel = count($tree);
if($tree[0]){ }
//render_page($user_id, 'frontend', 2, $type, $content_link)
//echo $taxonomy;
//explode("/", $path);
?>
<!-- Begin Body -->
<div class="container">
	<div class="row">
			<div class="col-xs-12">
              <div class="panel">
			  <?php render_page($user_id, 'frontend', 2, $type, $content_link); ?>
              <hr />
            </div>
		</div> 
	</div>
</div>