<?php
if(!isset($status)){
	list($status, $user) = auth_get_status();
	if($status !== AUTH_LOGGED){ die(); }
}
$page_view_data = mysqli_query($db_conn, "select content, count(*) as count from `".$_CONFIG['t_analytics']."` group by content");
?>
<table class="table table-striped table-bordered table-hover" id="table">
    <thead>
        <tr>
			<th><?php echo _('Title'); ?></th>
			<th><?php echo _('Type'); ?></th>
			<th><?php echo _('Publication date'); ?></th>
			<th class="center"><?php echo _('Views'); ?></th>
        </tr>
    </thead>
    <tbody>
	<?php foreach($page_view_data as $page_view){ ?>
	    <tr>
			<td></td>
			<td></td>
			<td></td>
			<td class="center"></td>
		</tr>
	<?php } ?>
	</tbody>
</table>
