<!-- Begin Body -->
<div class="row">
	<?php require('navigation.php'); ?>
	<div class="col-md-6">
		<div class="panel load-here">
		<h1><?php echo implode("/", $tree); ?></h1>
		<hr />
		<?php
		$result = mysqli_query($db_conn, "SELECT * FROM ".$_CONFIG['t_taxonomy']." WHERE type='".$tree[0]."' LIMIT 1");
		$taxonomy = mysqli_fetch_assoc($result);
		
		foreach(get_list($taxonomy['type'], $taxonomy['subtype'], "/".implode("/", $tree)."/") as $single_content){
			$content_info = get_content_info($single_content['id'], $single_content['value']);
			$taxonomy = get_taxonomy($single_content['id']);
			list($type, $subtype) = explode("/", $taxonomy);
			if($content_info['publish'] == true){ ?>
			<h2><?php echo $content_info['title']; ?></h2>
			<span class="pull-left"> - <?php echo ucfirst(_('publish'))." "._('from').": "; ?><strong><?php echo profile_img($content_info['author'], 'list');?></strong> - <?php echo get_real_name($content_info['author']); ?></strong> - <?php echo RelativeTime($content_info['date']); ?></span>
			<a href="<?php echo rtrim($path, "/").str_replace("title/", "", $content_info['link']);?>" class="btn btn-default pull-right"><?php echo _('Read'); ?></a>
			<div class="clearfix"></div>
			<hr />
			<?php 
			}
		}
		
		?>
		</div>
	</div>
	<?php require_once('sidebar.php'); ?>
</div>

