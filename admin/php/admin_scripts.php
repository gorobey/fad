<script>
$(document).ready(function () {
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
					$(".content-box-message").html('<div class="alert alert-danger" role="alert"><?php echo _('Error: Contact system administrator!');?></div>');
					$(".comfirm-box").slideDown('fast');
				},					
				success: function (result) {
					$(".content-box-message").html(result);
					$(".comfirm-box").slideDown('fast');
				}
			});		
		
		},
		onCancel: function (){
			$('.delete').confirmation('hide');
		}
	});
	
	$('#new_user, #new_attr, #user_edit, #new_content_filter').on("submit", function(e) {
		e.preventDefault();
		var action = $(this).attr('action');
		var data = $(this).serialize(); // check to show that all form data is being submitted
		$.ajax({
			type: "POST",
			url: action,
			data: data,
			dataType: "html",
			error: function(){
				$(".content-box-message").html('<div class="alert alert-danger" role="alert"><?php echo _('Error: Contact system administrator!');?></div>');
				$(".comfirm-box").slideDown('fast');
			},					
			success: function (result) {
				$(".content-box-message").html(result);
				$(".comfirm-box").slideDown('fast');
			}
		});
		$('.modal').modal('hide');
			return false;
	});

	$('#view_user').on('hide.bs.modal', function () {
	   $('#view_user').removeData();
	});
});
</script>
