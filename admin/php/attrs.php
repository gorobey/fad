<?php
require_once( "../../config.php");
require_once( "../../system/includes/auth.lib.php");
require_once( "../../system/includes/license.lib.php");
require_once("../../system/includes/utils.lib.php");
if(!isset($status)){auth_check_point();}

if($_GET['a'] == 'groups'){
	$attrs = get_groups_list();
}else{
	$attrs = get_roles_list();
}
$dimension = $_GET['a'];
?>
<div class="row">
	<div class="col-md-12" id="dashboard">
	     <h2><?php echo ucfirst(_($dimension)); ?></h2>
	     <p>
	     <?php
	     if($_GET['a']=="groups"){
		     echo _('The groups are the primary method for Organizing Users Their operation is vertical.<br />You can not belong to a group of More the Same time.');
	     }else{
		     echo _('The roles are the second method by which users are organized and then the permissions associated with them.<br />They are more granular groups and their operation is horizontal.');
	     } ?>
	     </p>
	     <div class="comfirm-box">
   		     <span class="fa fa-times"></span>
		     <div class="content-box-message">
		     </div>
	     </div>
		 <hr />
	</div>
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="panel panel-default">
	        <div class="panel-heading">
	            <?php echo ucfirst($_GET['a'])." "._('list');?> <button data-toggle="modal" data-target="#new-group-role" class="right btn btn-primary btn-xs" ><?php echo _('New')." "._($_GET['a']);?></button>
	        </div>
	        <div class="panel-body">
	             <table class="table table-striped table-bordered table-hover" id="table">
	                <thead>
	                    <tr>
	                        <th class="sort_disabled text-center"><span class="fa fa-trash"></span></th>
	                        <th><span class="fa fa-sort-numeric-asc"></span></th>
	                        <th><?php echo _('Group');?></th>
	                    </tr>
	                </thead>
	                <tbody>
						<?php
						$row_n = 1;
						foreach($attrs as $attr){ ?>
						    <tr id="<?php echo "row-".$row_n;?>">
						        <td class="text-center">
						            <a class="delete" data-toggle="confirmation" data-placement="right" data-href="php/attrs/edit_attr.php?action=d&id=<?php echo $attr['id']; ?>&dimension=<?php echo $_GET['a'];?>">
						            	<span class="fa fa-trash-o"></span>
						            </a>
						        </td>
						        <td><?php echo $attr['id']; ?></td>
						        <td><?php echo $attr['name']; ?></td>
						    </tr>
						<?php	
							$row_n++;
						} ?>
	                </tbody>
	            </table>
	        </div>
	    </div>
	</div>
</div><!-- /. ROW  -->

<!-- Modal -->
<div class="modal fade" id="new-group-role" tabindex="-1" role="dialog" aria-labelledby="New<?php echo ucfirst($_GET['a']);?>">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="New<?php echo ucfirst($_GET['a']);?>"><?php echo _('New')." "._($_GET['a']);?></h4>
      </div>
              <form name="new_attr" class="modal-form" id="new_attr" method="POST" action="php/attrs/edit_attr.php">
      <div class="modal-body">

	        <div class="form-group">
	        	<label for="name"><?php echo _('Name')?>:</label>
	            <input type="text" name="name" placeholder="<?php echo _('Name');?>" class="form-control">
	            <input type="hidden" name="action" value="n">
	            <input type="hidden" name="dimension" value="<?php echo $_GET['a']; ?>">
			</div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><?php echo _('Add')." "._($_GET['a']);?></button>
      </div>
              </form>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function() {
	$('#table').dataTable({
		"aoColumnDefs": [{ "bSortable": false, "aTargets": [ 0 ] }]
	});
});
</script>
<?php require('admin_scripts.php');
