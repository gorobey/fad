<?php
if(!isset($status)){auth_check_point();}
if(!ctype_digit($_GET['level'])){ die(); } ?>
<table class="table table-striped table-bordered table-hover" id="table">
    <thead>
        <tr>
			<?php if($_GET['level'] == 1){ ?>
				<th class="sort_disabled text-center"><span class="fa fa-trash"></span></th>
				<th><?php echo _('Taxonomy'); ?></th>
				<th><?php echo _('Title'); ?></th>
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
					<a class="delete" data-toggle="confirmation" data-placement="right" data-href="php/contents/edit_content.php?action=d&id=<?php echo $single_filter['id']; ?>">
						<span class="fa fa-trash-o"></span>
					</a>
				</td>
				<td><?php echo $_GET['type']; ?></td>
				<td><a href="php/contents/edit_contents.php?action=a&level=1&id=<?php echo $single_filter['id']; ?>&type=<?php echo $_GET['type']."&subtype=".$single_filter['subtype']; ?>" class="ajax"><?php echo $single_filter['value'];?></a></td>
				<td class="text-center"><a href="php/contents/edit_contents.php?action=a&level=1&id=<?php echo $single_filter['id']; ?>&type=<?php echo $_GET['type']."&subtype=".$single_filter['subtype']; ?>" class="ajax">Count</a></td>	
			<?php
				$i++; ?>
            </tr>
			<?php
		}
	}elseif($_GET['level'] == 2) {
		$i=1;
		foreach(get_list($_GET['type']) as $single_content){
			$content_info = get_content_info($single_content['id']); ?>
			<tr>
				<td class="text-center">
					<a class="delete" data-toggle="confirmation" data-placement="right" data-href="php/contents/edit_content.php?action=d&id=<?php echo $single_content['rel']; ?>">
						<span class="fa fa-trash-o"></span>
					</a>
				</td>
				<td class="center"><input type="checkbox"<?php if($content_info['publish']){ echo " checked "; }?>/></td>
				<td><?php echo get_taxonomy($single_content['id']); ?></td>
				<td><a href="php/contents/edit_contents.php?action=e&level=2&id=<?php echo $single_content['id']; ?>" class="ajax"><?php echo $content_info['title']; ?></a></td>
				<td><?php echo get_real_name($content_info['author']) ?></td>
				<td><?php echo RelativeTime($content_info['date']); ?></td>
			</tr>
			<?php
			$i++;
		}
	} ?>
    </tbody>
</table>
<!-- Modal -->
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo _('New')." ".ucfirst(_($_GET['type'])); ?></h4>
      </div>
      <div class="modal-body">
        <form name="new_user">
	        <div class="form-group">
	        	<label for="name"><?php echo _('Name');?>:</label>
	            <input type="text" name="name" placeholder="Nome" class="form-control">
			</div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary"><?php echo _('Add');?></button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function () {
	$('.selectpicker').selectpicker();
	$('#table').dataTable();
	$('.delete').confirmation({
		singleton: true,
		title: "Sicuro di eliminare?",
		popout: true,
		btnCancelLabel: "Annulla",
		btnOkLabel: "Ok",
		onConfirm: function (){
			alert($(this).attr('href'));
		},
		onCancel: function (){
			$('.delete').confirmation('hide');
		}
	});

	$('#new').on('hide.bs.modal', function () {
	   $('#new').removeData();
	});
});
</script>
