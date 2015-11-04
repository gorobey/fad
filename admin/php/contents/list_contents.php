<?php
if(!isset($status)){
	list($status, $user) = auth_get_status();
	if($status !== AUTH_LOGGED){ die(); }
}
if(!ctype_digit($_GET['level'])){ die(); } ?>
<table class="table table-striped table-bordered table-hover" id="table">
    <thead>
        <tr>
			<?php if($_GET['level'] == 1){ ?>
				<th class="sort_disabled text-center"><span class="fa fa-trash"></span></th>
				<th class="center"><?php echo _('Title'); ?></th>
				<th class="center"><?php echo _('Count'); ?></th>
			<?php }elseif($_GET['level'] == 2){ ?>
				<th class="sort_disabled text-center"><span class="fa fa-trash"></span></th>
				<th class="text-center"><i class="fa fa-eye"></i></th>
				<th class="center"><?php echo _('Title'); ?></th>
				<th class="center"><?php echo _('Author'); ?></th>
				<th class="center"><?php echo _('Date'); ?></th>
			<?php } ?>
        </tr>
    </thead>
    <tbody>
	<?php
	$i=1;
	foreach(get_taxonomy($_GET['type']) as $single_content){ ?>
	    <tr>
			<?php if($_GET['level'] == 1){ ?>
				<td class="text-center">
					<a class="delete" data-toggle="confirmation" data-placement="right" data-href="php/contents/edit_content.php?action=d&id=<?php echo $single_content['id']; ?>">
						<span class="fa fa-trash-o"></span>
					</a>
				</td>
				<td><a href="php/contents/edit_contents.php?action=a&level=1&id=<?php echo $single_content['id']; ?>&type=<?php echo $_GET['type']."&subtype=".$single_content['subtype']; ?>" class="ajax"><?php echo $single_content['value'];?></a></td>
				<td><a href="php/contents/edit_contents.php?action=a&level=1&id=<?php echo $single_content['id']; ?>&type=<?php echo $_GET['type']."&subtype=".$single_content['subtype'];; ?>" class="ajax">Count</a></td>				
			<?php } elseif($_GET['level'] == 2) { ?>
				<td class="text-center">
					<a class="delete" data-toggle="confirmation" data-placement="right" data-href="php/contents/edit_content.php?action=d&id=<?php echo $single_content['rel']; ?>">
						<span class="fa fa-trash-o"></span>
					</a>
				</td>
				<td><input type="checkbox" /></td>
				<td><a href="php/contents/edit_contents.php?action=e&level=2&id=<?php echo $single_content['rel']; ?>" class="right btn btn-primary btn-xs ajax">Tilte</a></td>
				<td>Author</td>
				<td>Date</td>
			<?php } ?>
            </tr>

			<?php	
				$i++;
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
