<?php if(!isset($status)){auth_check_point();} ?>
<table class="table table-striped table-bordered table-hover" id="analyticsStable">
    <thead>
        <tr>
			<th class="hidden-xs hidden-sm visible-md visible-lg"><?php echo _('Taxonomy'); ?></th>
			<th><?php echo _('Title'); ?></th>
			<th class="hidden-xs hidden-sm visible-md visible-lg"><?php echo _('Author'); ?></th>
			<th><?php echo _('Publication date'); ?></th>
			<th class="center"><?php echo _('Views'); ?></th>
        </tr>
    </thead>
    <tbody>
	<?php foreach(get_analytics() as $content){
		$content_info = get_content_info($content['content']);
		 ?>
	    <tr>
			<td class="hidden-xs hidden-sm visible-md visible-lg"><?php echo get_taxonomy($content['content']); ?></td>
			<td><?php echo $content_info['title']; ?></td>
			<td class="hidden-xs hidden-sm visible-md visible-lg"><?php echo get_real_name($content_info['author']); ?></td>
			<td><?php echo RelativeTime($content_info['date']);?></td>
			<td class="center"><?php echo $content['count'];?></td>
		</tr>
	<?php } ?>
	</tbody>
</table>
<script>
	$(document).ready(function () {
		$('#analyticsStable').dataTable({"pageLength": 50});
	});
</script>
