$(document).ready(function(){
	var offset = $( "#sidebar" ).offset();
	
	$('#sidebar').affix({
	      offset: {
	        top: offset.top
	      }
	});
	
	$("#up_img_p").bind("click", function () {
		$('#editp').trigger('click');
	});
	
	$("#editp").on("change", function () {
		$("#upload").ajaxForm({
			success: function(data){
				if(data != '') {
					$(".content-box-message").html(data);
					$(".comfirm-box").slideDown('fast');
				}
			}
		}).submit();
	});	
	
	
	var WH = $(window).height();
	$('.sidebar-scroll ul').css("max-height", WH);
	$('.sidebar-scroll').scrollbar();
	
	$("#showmenu").click(function(e){
		e.preventDefault();
		$("#menu").toggleClass("show");
	});
	$("#menu a i").click(function(event){
		event.preventDefault();
		if($(this).parent().next('ul').length){
			$(this).parent().next().toggle('fast');
			$(this).parent().children('i:last-child').toggleClass('fa-plus-circle fa-minus-circle');
		}
	});
	
$("a.ajax").on('click', function(e) {
	e.preventDefault();
	var href = $(this).attr('href');
	var load_here = $(".load-here");
	$.ajax({
		dataType: "html",
		type: "POST",
		url: href,		
		error: function(){		
			// Load the content in to the page.
			load_here.html("<p class='loading-error text-center'>Oops! Errore di caricamento!</p>");
		},
		
		beforeSend: function(){
            load_here.empty();
			load_here.addClass('preload-content');
		},
				
		success: function (result) {
			load_here.removeClass('preload-content').html(result);
		}
	});

	});
	
});
$( window ).resize(function() {
	var WH = $(window).height();
	$('.sidebar-scroll ul').css("max-height", WH);
});