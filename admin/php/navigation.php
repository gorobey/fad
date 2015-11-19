<?php
//http://wizardinternetsolutions.com/articles/web-programming/single-query-dynamic-multi-level-menu
require_once( "../../config.php");
require_once( "../../system/includes/auth.lib.php");
require_once( "../../system/includes/license.lib.php");
require_once("../../system/includes/utils.lib.php");
if(!isset($status)){auth_check_point();} ?>
<link rel="stylesheet" type="text/css" href="../system/style/css/jquery.domenu.css"/>

<div class="row">
	<div class="col-md-12" id="dashboard">
	     <h2><?php echo _('Navigation'); ?></h2>
	     <div class="comfirm-box">
   		     <span class="fa fa-times"></span>
		     <div class="content-box-message">
		     </div>
	     </div>
		 <hr />
	</div>
</div><!-- /. ROW  -->
<div class="row">
	<div class="col-xs-12">
	    <div class="panel panel-default">
	        <div class="panel-heading"><?php echo _("Navigation"); ?>
	        <button type="submit" class="btn btn-primary btn-xs save-menu pull-right"><?php echo _('Save now!');?></button>
	        </div>
			<div class="panel-body">
				<div class="col-xs-12">
					<ul class="nav nav-tabs">
						<?php lang_menu("tab"); ?>
					</ul>
					<br />
				</div>
				<div class="dd" id="domenu">

					<button id="domenu-add-item-btn" class="dd-new-item">+</button>
					<!-- .dd-item-blueprint is a template for all .dd-item's -->
					<li class="dd-item-blueprint">
						<div class="dd-handle dd3-handle"></div>
						<div class="dd3-content">
							<span>[item_name]</span>
							<div class="button-container">
								<button class="item-add">+</button>
								<button class="item-remove" data-confirm-class="item-remove-confirm">&times;</button>
							</div>
							<div class="dd-edit-box" style="display: none;">
								<input type="text" name="title" autocomplete="off" placeholder="Item" data-placeholder="<?php echo _('Label'); ?>" data-default-value="<?php echo _('menu voice'); ?> {?numeric.increment}">
								<input type="text" name="classname" autocomplete="off" placeholder="Classname" data-placeholder="">
								<?php
								$taxQ = mysqli_query($db_conn, "SELECT id, type, subtype FROM `".$_CONFIG['t_taxonomy']."`");
								echo "<select name='link'>
								<option>/</option>";
								while($taxonomy = mysqli_fetch_assoc($taxQ)){
									$get_list = get_list($taxonomy['type'], $taxonomy['subtype']);
									if(count($get_list)>0){
										echo '<option value="'.str_replace(" ", "-", $taxonomy['type']).'/">'.str_replace(" ", "-", $taxonomy['type']).'/</option>';
										foreach($get_list as $single_content){
											if(count($single_content)>0){
												$content_info = get_content_info($single_content['id']);
												echo '<option value="'.str_replace(" ", "-", get_taxonomy($single_content['id'], 2)).'">'.str_replace(" ", "-", get_taxonomy($single_content['id'], 2)).'</option>';//.'/'.$taxonomy['subtype']
												echo '<option value="'.str_replace(" ", "-", get_taxonomy($single_content['id'], 2).$content_info['title']).'">'.str_replace(" ", "-", get_taxonomy($single_content['id'], 2).$content_info['title']).'</option>';//.'/'.$taxonomy['subtype']
											}
										}
									}
								}
								echo "</select>";
								?>
								<i class="end-edit">&#x270e;</i>
							</div>
						</div>
					</li><!-- end template -->
					<ol class="dd-list"></ol>
				</div>
			</div>		
		</div>
		</div>
		</div>
		<form id="save_navigation" method="POST" action="php/navigation/edit_navigation.php">
			<input class="nav-tree" type="hidden" name="nav-tree" value="" />
		</form>
</div>

    <script src="../system/js/jquery.domenu.js"></script>
    <script>

    $(document).ready(function() {
	    
        var updateOutput = function(e) {
            var list   = e.length ? e : $(e.target),
                output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.domenu('serialize')));//, null, 2));
            } else {
                output.val('JSON browser support required for this demo.');
            }
        };

        $('#domenu').domenu({
            slideAnimationDuration: 0,
            onDomenuInitialized: [function() {
                //console.log('event: onDomenuInitialized', 'arguments:', arguments, 'context:', this);
            }],
            data: '<?php $Jmenu = get_info('nav-'.$_SESSION['locale']); if( isJson($Jmenu)){ echo $Jmenu; }else{ echo "[{}]";} ?>'// insert here the data
        }).parseJson();
                $('.save-menu').on('click', function(){
	                $('input.nav-tree').val($('#domenu').domenu().toJson());
	                $('#save_navigation').submit();	           
                });
                
    });
    </script>
<?php require('admin_scripts.php');
