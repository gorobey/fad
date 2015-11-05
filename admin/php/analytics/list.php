<?php
if(!isset($status)){
	list($status, $user) = auth_get_status();
	if($status !== AUTH_LOGGED){ die(); }
}
?>
<table class="table table-striped table-bordered table-hover" id="table">
    <thead>
        <tr>
			<th><?php echo _('Taxonomy'); ?></th>
			<th><?php echo _('Title'); ?></th>
			<th><?php echo _('Author'); ?></th>
			<th><?php echo _('Publication date'); ?></th>
			<th class="center"><?php echo _('Views'); ?></th>
        </tr>
    </thead>
    <tbody>
	<?php foreach(get_analytics() as $content){
		$page_info = get_content_info($content['content']);
		 ?>
	    <tr>
			<td><?php echo get_taxonomy($content['content']); ?></td>
			<td><?php echo $page_info['title']; ?></td>
			<td><?php echo get_real_name($page_info['author']); ?></td>
			<td><?php echo RelativeTime($page_info['date']);?></td>
			<td class="center"><?php echo $content['count'];?></td>
		</tr>
	<?php } ?>
	</tbody>
</table>
<script>
	$(document).ready(function () {
		$('#table').dataTable({"pageLength": 50});
	});
</script>
