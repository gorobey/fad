<?php
require_once( "../../config.php");
require_once( "../../system/includes/auth.lib.php");
require_once( "../../system/includes/license.lib.php");
require_once("../../system/includes/utils.lib.php");
list($status, $user) = auth_get_status();
if($status !== AUTH_LOGGED){ die(); } ?>
<div class="row">
	<div class="col-md-12" id="dashboard">
	     <h2><?php echo ucfirst($_GET['type']); ?></h2>
	     <div class="comfirm-box fa fa-times"></div>
		 <hr />
	</div>

        <?php render_page('admin', $_GET['type'],$user_id);?>
        
<div class="col-md-12 col-sm-12 col-xs-12">                     
    <div class="panel panel-default">
        <div class="panel-heading">
            Lista <?php echo $_GET['type']; ?><button data-href="content_new.php" data-toggle="modal" data-target="#new" class="right btn btn-primary btn-xs">Nuovo <?php echo ucfirst($_GET['type']); ?></button>
        </div>
        <div class="panel-body">       
             <table class="table table-striped table-bordered table-hover" id="ContentsTable">
                <thead>
                    <tr>
                        <th class="center"><span class="fa fa-trash"></span></th>
                        <th class="center">Titolo</th>
                        <th class="center">Autore</th>
                        <th class="center">Stato</th>
                        <th class="center">Data</th>
                    </tr>
                </thead>
                <tbody>
				<?php
				$i=1;
				foreach(get_contents($_GET['type']) as $single_content){ ?>
				    <tr class="<?php if ($i & 1) {
									    echo 'even';
									} else {
									    echo 'odd';
									} ?>">
				        <td class="center">
				            <a class="delete" data-toggle="confirmation" data-placement="right" data-href="php/contents/edit_content.php?id=<?php echo $single_content['id']; ?>">
				            	<span class="fa fa-trash-o"></span>
				            </a>
				        </td>

                        <td class="center">Titolo</td>                        
                        <td class="center">Autore</td>
                        <td class="center">Data</td>
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
			        <h4 class="modal-title" id="myModalLabel">Nuovo <?php echo ucfirst($_GET['type']); ?></h4>
			      </div>
			      <div class="modal-body">
			        <form name="new_user">
				        <div class="form-group">
				        	<label for="name">Nome:</label>
				            <input type="text" name="name" placeholder="Nome" class="form-control">
						</div>
			        </form>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
			        <button type="button" class="btn btn-primary">Aggiungi</button>
			      </div>
			    </div>
			  </div>
			</div>
		    <script>
			$(document).ready(function () {
				$('.selectpicker').selectpicker();
				$('#ContentsTable').dataTable();
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
        </div>
    </div>
</div>