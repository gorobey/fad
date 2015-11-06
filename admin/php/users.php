<?php
require_once( "../../config.php");
require_once( "../../system/includes/auth.lib.php");
require_once( "../../system/includes/license.lib.php");
require_once("../../system/includes/utils.lib.php");
auth_check_point(); ?>
<div class="row">
	<div class="col-md-12" id="dashboard">
	     <h2><?php echo _('Users');?></h2>
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
	            <?php echo _('Users list');?> (<?php echo get_users_count();?>)<button data-toggle="modal" data-target="#new" class="right btn btn-primary btn-xs"><?php echo _('New User'); ?></button>
	        </div>
	        <div class="panel-body">
	             <table class="table table-striped table-bordered table-hover" id="table">
	                <thead>
	                    <tr>
	                        <th class="sort_disabled text-center"><span class="fa fa-trash"></span></th>
	                        <th><?php echo _('Name');?></th>
	                        <th><?php echo _('Surname');?></th>
	                        <th class="hidden-xs hidden-sm visible-md visible-lg"><?php echo _('E-Mail');?></th>
	                        <th><?php echo _('Group'); ?></th>
	                        <th class="hidden-xs hidden-sm visible-md visible-lg"><?php echo _('Registration'); ?></th>
	                    </tr>
	                </thead>
	                <tbody>
						<?php
						$i=1;
						foreach(get_users_list() as $single_user){ ?>
						    <tr <?php if($single_user['temp']==1){echo " style='opacity:.7;'";}?>
							    class="<?php if ($i & 1) {
										    echo 'even';
										} else {
										    echo 'odd';
										} ?>">
						        <td class="text-center">
						            <span class="delete" data-toggle="confirmation" data-placement="right" data-href="php/users/edit_user.php?a=d&id=<?php echo $single_user['id']; ?>">
						            	<span class="fa fa-trash-o"></span>
						            </span>
						        </td>
						        <td>
						        	<a data-remote="php/users/view_user.php?id=<?php echo $single_user['id']; ?>" data-toggle="modal" data-target="#view_user"><?php echo ucfirst($single_user['name']); ?></a>
						        <td>
							        <a data-remote="php/users/view_user.php?id=<?php echo $single_user['id']; ?>" data-toggle="modal" data-target="#view_user"><?php echo ucfirst($single_user['surname']); ?></a>
						        </td>
						        <td class="hidden-xs hidden-sm visible-md visible-lg">
							        <a data-remote="php/users/view_user.php?id=<?php echo $single_user['id']; ?>" data-toggle="modal" data-target="#view_user"><?php echo $single_user['mail']; ?></a>
						        </td>
						        <td>
								<a data-remote="php/users/view_user.php?id=<?php echo $single_user['id']; ?>" data-toggle="modal" data-target="#view_user"><?php echo ucfirst(get_user_attr($single_user['id'], "g"));?></a>
						        </td>
						        <td class="hidden-xs hidden-sm visible-md visible-lg">
								<a data-remote="php/users/view_user.php?id=<?php echo $single_user['id']; ?>" data-toggle="modal" data-target="#view_user"><?php $user_info = user_get_info($single_user['id']); echo date("d/m/Y", $user_info['regdate']);?></a>
						        </td>
						    </tr>
						<?php $i++; } ?>
	                </tbody>
	            </table>
	        </div>
	    </div>
	</div>
</div><!-- /. ROW  -->

<!-- Modal -->
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="NewUser">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-user" id="NewUser"><?php echo _('Add User');?></h4>
      </div>
      <form name="new_user" class="modal-form" id="new_user" method="POST" action="php/users/edit_user.php">
	      <div class="modal-body">
	        <div class="form-group">
	        	<label for="name">Nome:</label>
	            <input type="text" name="name" placeholder="<?php echo _('Name');?>" class="form-control">
			</div>
			<div class="form-group">
	        	<label for="surname">Cognome:</label>
	            <input type="text" name="surname" placeholder="<?php echo _('Surname');?>" class="form-control">
	        </div>
	   		<div class="form-group">
	        	<label for="mail">E-mail:</label>
	            <input type="email" name="mail" placeholder="<?php echo _('E-mail');?>" class="form-control">
	        </div>         
			<div class="form-group">	
				<label for="group">Gruppo:</label><!--forse meglio con check box...-->
				<select class="form-control selectpicker show-tick" id="group" name="group">
				<?php
				foreach(arr_groups() AS $single_group){
					echo "<option value='".$single_group['id']."'>".ucfirst($single_group['name'])."</option>";
				} ?>
				</select>
			</div>
			<div class="form-group">
				<label for="<?php echo _('Role');?>"><?php echo _('Role');?>:</label>
				<select class="form-control selectpicker show-tick" id="role" name="role" multiple>
				<?php
				foreach(arr_roles() AS $single_roles){
					echo "<option value='".$single_roles['id']."'>".ucfirst($single_roles['name'])."</option>";
				} ?>
				</select>
				<input type="hidden" name="a" id="a" value="n" />
			</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal" data-dismiss="modal"><?php echo _('Reset');?></button>
	        <button type="submit" class="btn btn-primary"><?php echo _('Add');?></button>
	      </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="view_user" tabindex="-1" role="dialog" aria-labelledby="UserView">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content"></div>
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
