<?php
require_once( "../../config.php");
require_once( "../../system/includes/auth.lib.php");
require_once( "../../system/includes/license.lib.php");
require_once("../../system/includes/utils.lib.php");
list($status, $user) = auth_get_status();

if($status !== AUTH_LOGGED){ die(); } ?>

<table class="table table-striped table-bordered table-hover" id="table">
    <thead>
        <tr>
            <th class="sort_disabled text-center"><span class="fa fa-trash"></span></th>
            <th class="text-center"><i class="fa fa-eye"></i></th>
            <th><?php echo _('Title');?></th>
            <th><?php echo _('Author');?></th>
            <th><?php echo _('Date');?></th>
        </tr>
    </thead>
    <tbody>
	<?php
	$i=1;
	foreach(get_contents($_GET['type']) as $single_content){ ?>
	    <tr>
	        <td class="text-center">
	            <a class="delete" data-toggle="confirmation" data-placement="right" data-href="php/contents/edit_content.php?a=delete&id=<?php echo $single_content['id']; ?>">
	            	<span class="fa fa-trash-o"></span>
	            </a>
	        </td>
            <td><input type="checkbox" /></td>
            <td><!--?id=NUM&action=e--></td>
            <td></td>
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