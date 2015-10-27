<?php $inc = hash_equals($_CONFIG['tabasco'], crypt($_CONFIG['pepper'], $_CONFIG['tabasco'])) or die(); ?>
<link rel="stylesheet" type="text/css" href="../system/modules/editor/css/bootstrap3-wysihtml5.min.css"></link>
<style>
.wysihtml5-sandbox{  border: 1px solid #ccc !important; border-radius: 5px !important; padding: 10px !important}
</style>

<div class="form-group">
	<textarea class="textarea" placeholder="" style="width: 100%; height: 500px; font-size: 14px; line-height: 18px;"></textarea>
</div>

<script src="../system/modules/editor/js/wysihtml5x-toolbar.min.js"></script>
<script src="../system/modules/editor/js/handlebars.runtime.min.js"></script>
<script src="../system/modules/editor/js/bootstrap3-wysihtml5.min.js"></script>

<script>
function load_image_gallery(obj) {
        var container = $(obj).closest('.bootstrap-wysihtml5-insert-image-modal.in');
        var library = container.find(".img-library");

        $(".bootstrap-wysihtml5-insert-image-modal .img-library").load("../admin/php/media/view_media.php?include");
        $("#ModalMedia").modal({
	        show: true
        });
        
        $('#ModalMedia').on('hidden', function(){
			$(this).data('modal', null);
		});
        
		$('.wysihtml5-sandbox').contents().find(".wysihtml5-editor").bind("change", function(){
			//$(this).contents().find('img').on('click', function () {
				alert("click!");
			//});
		})

      }
      
      var buttons = {
            textAlign: function(locale) {
		        return "<li><div class='btn-group'>" +
	            "<a class='btn btn-default' data-wysihtml5-command='justifyLeft' data-wysihtml5-command-value='&justifyLeft;' title= 'Align text left'>" +
	            "<span class='glyphicon glyphicon-align-left'></span></a>" +
	            "<a class='btn btn-default' data-wysihtml5-command='justifyCenter' data-wysihtml5-command-value='&justifyCenter;' title= 'Align text center'>" +
	            "<span class='glyphicon glyphicon-align-center'></span></a>" +
	            "<a class='btn btn-default' data-wysihtml5-command='justifyRight' data-wysihtml5-command-value='&justifyRight;' title= 'Align text right'>" +
	            "<span class='glyphicon glyphicon-align-right'></span></a>" +
	            "</li>";
		    },
            image: function(locale){
              return [
                  '<li>'
                    ,'<div class="bootstrap-wysihtml5-insert-image-modal modal fade" id="ModalMedia" tabindex="-1" role="dialog" aria-labelledby="ModalMedia">'
                      ,'<div class="modal-dialog modal-lg">'
                        ,'<div class="modal-content">'
                          ,'<div class="modal-header">'
                            ,'<a class="close" data-dismiss="modal">&times;</a>'
                            ,'<h4>Insert image</h4>'
                          ,'</div>'
                          ,'<div class="modal-body">'
                            ,'<div class="img-library">Loading image library ...</div>'
                            ,'<input value="http://" class="bootstrap-wysihtml5-insert-image-url form-control">'
                          ,'</div>'
                          ,'<div class="modal-footer">'
                            ,'<a class="btn btn-default" data-dismiss="modal">Cancel</a>'
                            ,'<a class="btn btn-primary" data-dismiss="modal">Insert image</a>'
                          ,'</div>'
                        ,'</div>'
                      ,'</div>'
                    ,'</div>'
                  ,'<a class="btn btn-default" data-wysihtml5-command="insertImage" onclick="load_image_gallery(this)" title="Insert image" tabindex="-1" style="margin-left:-4px">'
                  ,'<span class="fa fa-camera-retro"></span>'
                  ,'</a>'
                  ,'</li>'
              ].join('');
            }
          };

$('.textarea').wysihtml5({
  toolbar: {
    custom: true,
    "font-styles":true,
	'textAlign': true, // custom defined buttons to align text see myCustomTemplates variable above
	"emphasis":true,
	"lists":true,
	"html":true,
	"link":true,
	"image":true,
	"color":true,
	"blockquote":true,
	"outdent":true,
	"indent":true,
	"size": 'lg',
	'resize': true,
	'justify': true,
	"fa": true
  },
  customTemplates: buttons,
  "stylesheets": ["../system/modules/editor/css/wysiwyg-color.css"],
}); 

  $('.textarea').html('Some text dynamically set.');//DbToHtml
  var htmlToDB = $('.textarea').val();//HtmlToDB
</script>
