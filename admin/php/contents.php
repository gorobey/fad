<?php
require_once( "../../config.php");
require_once( "../../system/includes/auth.lib.php");
require_once( "../../system/includes/license.lib.php");
require_once("../../system/includes/utils.lib.php");
list($status, $user) = auth_get_status();
if($status !== AUTH_LOGGED || !ctype_digit($_GET['level'])){ die(); } ?>
<div class="row">
	<div class="col-md-12" id="dashboard">
	     <h2><?php echo ucfirst($_GET['type']); ?></h2>
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
            <?php echo _('List')." ".ucfirst(_($_GET['type']));
	            
	        if($_GET['level'] == 1){ ?>
            	<button data-toggle="modal" data-target="#new-filter" class="right btn btn-primary btn-xs"><?php echo _('New')." "._('content filter'); ?></button>
            <?php } ?>
        </div>
        <div class="panel-body">
			<?php require(ROOT.'/admin/php/contents/list_contents.php');
				if($_GET['level']==1){ ?>
<!-- Modal -->
<div class="modal fade" id="new-filter" tabindex="-1" role="dialog" aria-labelledby="NewFilter">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="NewFilter"><?php echo _('New')." "._('filter')." for ".ucfirst(_($_GET['type']));?></h4>
      </div>
      <form name="new_content_filter" id="new_content_filter" method="POST" action="php/contents/edit_contents.php">
	      <div class="modal-body">
		        <div class="form-group">
		        	<label for="name"><?php echo _('Name'); ?>:</label>
		            <input type="text" name="name" placeholder="<?php echo _('Name');?>" class="form-control">
					<label for="group"><?php echo _('type'); ?>:</label><!--forse meglio con check box...-->
					<select class="form-control selectpicker show-tick" id="subfilter" name="subfilter">
					<?php
					foreach(arr_item('admin',$_GET['type']) AS $single_item){
						echo "<option value='".$single_item."'>".ucfirst($single_item)."</option>";
					} ?>
					</select>
		            <input type="hidden" name="action" value="n">
		            <input type="hidden" name="filter" value="<?php echo $_GET['type']; ?>">
		            <input type="hidden" name="level" value="<?php echo $_GET['level']; ?>">
		            <input type="hidden" name="locale" value="<?php echo $_SESSION['locale']; ?>">
				</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary"><?php echo _('Add')." "._('new filter');?></button>
	      </div>
      </form>
    </div>
  </div>
</div>
				<?php } ?>
        </div>
    </div>
</div>
<?php require('admin_scripts.php');
