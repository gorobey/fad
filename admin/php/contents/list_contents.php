<?php
if(!isset($status)){auth_check_point();}
if(!ctype_digit($_GET['level'])){ die(); } ?>
<table class="table table-striped table-bordered table-hover" id="table">
    <thead>
        <tr>
			<?php if($_GET['level'] == 1){ ?>
				<th class="sort_disabled text-center"><span class="fa fa-trash"></span></th>
				<th><?php echo _('Taxonomy'); ?></th>
				<th><?php echo _('Filter'); ?></th>
				<th class="center"><?php echo _('Count'); ?></th>
			<?php }elseif($_GET['level'] == 2){ ?>
				<th class="sort_disabled text-center"><span class="fa fa-trash"></span></th>
				<th class="text-center"><i class="fa fa-eye"></i></th>
				<th><?php echo _('Taxonomy'); ?></th>
				<th><?php echo _('Title'); ?></th>
				<th><?php echo _('Author'); ?></th>
				<th><?php echo _('Date'); ?></th>
			<?php } ?>
        </tr>
    </thead>
    <tbody>
	<?php if($_GET['level'] == 1){
		$i=1; 
		foreach(get_filter($_GET['type']) as $single_filter){ ?>
			<tr>
				<td class="text-center">
					<a class="delete" data-toggle="confirmation" data-placement="right" data-href="php/contents/edit_contents.php?level=1&action=d&id=<?php echo $single_filter['id']; ?>">
						<span class="fa fa-trash-o"></span>
					</a>
				</td>
				<td><?php echo get_taxonomy($single_filter['id']); ?></td>		
				<td><a href="php/contents/edit_contents.php?action=a&level=1&id=<?php echo $single_filter['id']; ?>&type=<?php echo $_GET['type']."&subtype=".$single_filter['subtype']; ?>&link_content=<?php echo $single_filter['link'];?>" class="ajax"><?php echo $single_filter['value'];?></a></td>
				<td class="text-center"><a href="php/contents/edit_contents.php?action=a&level=1&id=<?php echo $single_filter['id']; ?>&type=<?php echo $_GET['type']."&subtype=".$single_filter['subtype']; ?>" class="ajax"><?php echo count(get_list($_GET['type'], $single_filter['subtype'])); ?></a></td>
			<?php $i++; ?>
            </tr>
		<?php
		}
	}elseif($_GET['level'] == 2) {
		$i=1;
		foreach(get_list($_GET['type'], $_GET['subtype']) as $single_content){
		print_r($single_content);
			$content_info = get_content_info($single_content['id'], $single_content['value']);
			
			print_r($content_info);echo "<br />";
			
			$taxonomy = get_taxonomy($single_content['id']);
			list($type, $subtype) = explode("/", $taxonomy);
			$taxonomy = get_taxonomy($single_content['id'], 2)."/";
		 ?>
			<tr>
				<td class="text-center">
					<a class="delete" data-toggle="confirmation" data-placement="right" data-href="php/contents/edit_contents.php?level=2&action=d&id=<?php echo $single_content['rel']; ?>">
						<span class="fa fa-trash-o"></span>
					</a>
				</td>
				<td class="center"><input name="publish" type="checkbox"<?php if($content_info['publish']){ echo " checked "; }?>/></td>
				<td><?php echo get_taxonomy($single_content['id'], 2)."/"; ?></td>
				<td><a href="php/contents/edit_contents.php?action=o&level=2&id=<?php echo $single_content['id']."&type=".$_GET['type']."&subtype=".$_GET['subtype']; ?>&link_content=<?php echo str_replace("title/", "", $content_info['link']); ?>" class="ajax"><?php echo $content_info['title']; ?></a></td>
				<td><?php echo get_real_name($content_info['author']) ?></td>
				<td><?php echo RelativeTime($content_info['date']); ?></td>
			</tr>
			<?php
			$i++;
		}
	} ?>
    </tbody>
</table>
