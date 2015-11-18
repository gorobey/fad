<?php if(!isset($status)){auth_check_point();} ?>
<link rel="stylesheet" type="text/css" href="../system/modules/<?php echo $val; ?>/css/bootstrap3-wysihtml5.min.css"></link>
<link rel="stylesheet" type="text/css" href="../system/modules/<?php echo $val; ?>/css/bootstrap-datetimepicker.min.css"></link>
<style>
.wysihtml5-sandbox{  border: 1px solid #ccc !important; border-radius: 5px !important; padding: 10px !important}
textarea{resize:vertical;}
</style>

<div class="col-xs-12 form-group">
	<textarea name="content[content]" class="textarea" style="width: 100%; height: 500px; font-size: 14px; line-height: 18px;"></textarea>
</div>
<script src="../system/modules/<?php echo $val; ?>/js/bootstrap3-wysihtml5-advanced.js"></script>
<script src="../system/modules/<?php echo $val; ?>/js/bootstrap3-wysihtml5.all.min.js"></script>
<script type="text/javascript" src="../system/js/moment.min.js"></script>
<script type="text/javascript" src="../system/js/bootstrap-datetimepicker.min.js"></script>
<script>
$(function() {
	$('.wysihtml5-supported .bootstrap-datetimepicker-widget').remove();
	$('#datetimepicker').datetimepicker({
	    pickDate: true,                 //en/disables the date picker
	    pickTime: true,                 //en/disables the time picker
	    useMinutes: true,               //en/disables the minutes picker
	    useSeconds: true,               //en/disables the seconds picker
	    useCurrent: true,               //when true, picker will set the value to the current date/time
	    minuteStepping:1,               //set the minute stepping
	    minDate:"1/1/1900",               //set a minimum date
	  //  maxDate: ,     //set a maximum date (defaults to today +100 years)
	    language:'en',                  //sets language locale
	    defaultDate:"",                 //sets a default date, accepts js dates, strings and moment objects
	    disabledDates:[],               //an array of dates that cannot be selected
	    enabledDates:[],                //an array of dates that can be selected
	    useStrict: false,               //use "strict" when validating dates  
	    sideBySide: true,              //show the date and time picker side by side
	    daysOfWeekDisabled:[]          //for example use daysOfWeekDisabled: [0,6] to disable weekends
	});
});


function load_image_gallery(obj) {
        $(".bootstrap-wysihtml5-insert-image-modal .img-library").load("../admin/php/media/view_media.php?include");
        $("#ModalMedia").modal({
	        show: true
        });
        

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
	"size": 'md',
	'resize': true,
	'justify': true,
	"fa": true,
  customTemplates: buttons,
  "stylesheets": [
  "../system/style/css/bootstrap.min.css",
  "../system/modules/editor/css/wysiwyg-color.css",
//"../system/modules/editor/css/bootstrap-debug.css"
  ],
  parserRules: wysihtml5ParserRules,
  hotKeys: {
    'ctrl+z meta+z': 'undo',
    'ctrl+y meta+y meta+shift+z': 'redo'
  }

}); 

$('.textarea').html('<?php if(isset($data['content'])){echo $data['content'];}?>');//DbToHtml
</script>
