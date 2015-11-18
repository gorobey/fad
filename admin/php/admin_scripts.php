<script>
$(document).ready(function () {
	var table;
	table = $('#table').dataTable();
	$('.selectpicker').selectpicker();
	$('.delete').confirmation({
		singleton: true,
		title: "<?php echo _('Are you sure you want to delete?')?>",
		popout: true,
		btnCancelLabel: "<?php echo _('Undo');?>",
		btnOkLabel: "<?php echo _('Ok!');?>",
		onConfirm: function (){
			var url = $(this).attr('data-href');
			var exp_url = (url.split('?'));
			var vars={} , hash="";
				var hashes = url.slice(url.indexOf('?') + 1).split('&');
				for(var i = 0; i < hashes.length; i++) {
				hash = hashes[i].split('=');
				vars[hash[0]] = hash[1];
			}
			$.ajax({
				type: "POST",
				url: exp_url[0],
				data: vars,
				dataType: "html",
				error: function(){		
					$(".content-box-message").html('<div class="alert alert-danger" role="alert"><?php echo _("Error: Contact system administrator!");?></div>');
					$(".comfirm-box").slideDown('fast');
				},					
				success: function (result) {
					$(".content-box-message").html(result);
					$(".comfirm-box").slideDown('fast');
					if($(".content-box-message div").hasClass('alert-success')){
						var rowid = $(this).parent().parent().attr('id');
						var rowNode = table
						.row( $("#"+rowid).remove() )
						.remove()
						.draw();
					}
				}			
			});
		},
		onCancel: function (){
			$('.delete').confirmation('hide');
		}
	});
	
	$('#new_user, #new_attr, #user_edit, #new_content_filter, #new_content, #save, #save_navigation').on("submit", function(e) {
		e.preventDefault();
		var action = $(this).attr('action');
		var data = $(this).serialize(); // check to show that all form data is being submitted
		$.ajax({
			type: "POST",
			url: action,
			data: data,
			dataType: "html",
			beforeSend: function(){
				$(".content-box-message").empty();
				$(".content-box-message").addClass('preload-comfirm');
				$(".comfirm-box").slideDown('fast');
			},
			error: function(){
				$(".content-box-message").removeClass('preload-comfirm');
				$(".content-box-message").html('<div class="alert alert-danger" role="alert"><?php echo _("Error: Contact system administrator!");?></div>');
			},
			success: function (result) {
				$(".content-box-message").removeClass('preload-comfirm');
				$(".content-box-message").html(result);				
			}
		});
		$('.modal').modal('hide');
		return false;
	});

	$('.modal').on('hide.bs.modal', function () {
	   $(this).removeData();
	});
});
</script>
