<?php
//http://wizardinternetsolutions.com/articles/web-programming/single-query-dynamic-multi-level-menu
require_once( "../../config.php");
require_once( "../../system/includes/auth.lib.php");
require_once( "../../system/includes/license.lib.php");
require_once("../../system/includes/utils.lib.php");
if(!isset($status)){auth_check_point();} ?>
<link rel="stylesheet" type="text/css" href="jquery.domenu.css"/>
<style>
    .cf:after {
        visibility: hidden;
        display: block;
        font-size: 0;
        content: " ";
        clear: both;
        height: 0;
    }

    * html .cf {zoom: 1;}

    *:first-child+html .cf {zoom: 1;}

    html {
        margin: 0;
        padding: 0;
    }

    body {
        font-size: 100%;
        margin: 50px;
        font-family: 'Helvetica Neue', Arial, sans-serif;
    }

    h1 {
        font-size: 1.75em;
        margin: 0 0 0.6em 0;
    }

    a {color: #2996cc;}

    a:hover {text-decoration: none;}

    p {line-height: 1.5em;}

    .small {
        color: #666;
        font-size: 0.875em;
    }

    .large {font-size: 1.25em;}
</style>
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
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="panel panel-default">
	        <div class="panel-heading">
		        <?php echo _("Navigation"); ?>
			</div>


            <div class="dd" id="domenu-1">

                <button id="domenu-add-item-btn" class="dd-new-item">+</button>
                <!-- .dd-item-blueprint is a template for all .dd-item's -->
                <li class="dd-item-blueprint">
                    <div class="dd-handle dd3-handle">Drag</div>
                    <div class="dd3-content">
                        <span>[item_name]</span>
                        <!-- @migrating-from 0.13.29 button container-->
                        <div class="button-container">
                            <!-- @migrating-from 0.13.29 add button-->
                            <button class="item-add">+</button>
                            <button class="item-remove" data-confirm-class="item-remove-confirm">&times;</button>
                        </div>
                        <div class="dd-edit-box" style="display: none;">
                            <!-- data-placeholder has a higher priority than placeholder -->
                            <!-- data-placeholder can use token-values; when not present will be set to "" -->
                            <!-- data-default-value specifies a default value for the input; when not present will be set to "" -->
                            <input type="text" name="title" autocomplete="off" placeholder="Item" data-placeholder="Any nice idea for the title?" data-default-value="doMenu List Item. {?numeric.increment}">
                            <input type="text" name="tagline" autocomplete="off" placeholder="tagline" data-placeholder="">
<?php
	$taxQ = mysqli_query($db_conn, "SELECT id, type, subtype FROM `".$_CONFIG['t_taxonomy']."`");
	echo "<select name='superselect'>
		<option>"._('Select...')."</option>";
	while($taxonomy = mysqli_fetch_assoc($taxQ)){
		$itemsQ = mysqli_query($db_conn,"SELECT `".$_CONFIG['t_item']."`.rel, `".$_CONFIG['t_locale']."`.value FROM `".$_CONFIG['t_item']."` INNER JOIN `".$_CONFIG['t_locale']."` WHERE `".		$_CONFIG['t_item']."`.rel = ".$taxonomy['id']." AND `".$_CONFIG['t_item']."`.rel = `".$_CONFIG['t_locale']."`.rel AND `".$_CONFIG['t_locale']."`.key = 'title'");
		$num_items = mysqli_num_rows($itemsQ);
		if($num_items>0){
			echo "<optgroup label='".$taxonomy['type']." / ".$taxonomy['subtype']."'>";
			while($item = mysqli_fetch_assoc($itemsQ)){
				$content_info = get_content_info($item['rel']);

				echo '<option>'.$content_info['title'].'</option>';
			}
			echo '</optgroup>';
		}
	}
	echo "</select>";
	?>
                            <!-- @migrating-from 0.13.29 an element ".end-edit" within ".dd-edit-box" exists the edit mode on click -->
                            <i class="end-edit">&#x270e;</i>
                        </div>
                    </div>
                </li>

                <ol class="dd-list"></ol>
            </div>





				</div>		
			</div>
	</div>
</div>

    <script src="../system/js/jquery.domenu.js"></script>
    <script>

    $(document).ready(function()
    {

        var updateOutput = function(e)
        {
            var list   = e.length ? e : $(e.target),
                output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.domenu('serialize')));//, null, 2));
            } else {
                output.val('JSON browser support required for this demo.');
            }
        };

        $('#domenu-1').domenu({
            slideAnimationDuration: 0,
            onDomenuInitialized: [function() {
                console.log('event: onDomenuInitialized', 'arguments:', arguments, 'context:', this);
            }],
            data: '[{"id":11,"title":"doMenu List Item","http":"","superselect":"2"},{"id":10,"title":"News","http":"","superselect":"1"},{"id":9,"title":"Categories","http":"","superselect":"1"},{"id":6,"title":"Shop","http":"","children":[{"id":5,"title":"Glass","http":"","superselect":"1"},{"title":"Other","superselect":"select something..."}],"superselect":"select something..."},{"id":1,"title":"About","http":"","superselect":"select something..."}]'
        }).parseJson()
                .onParseJson(function() {
                    console.log('event: onFromJson', 'arguments:', arguments, 'context:', this);
                })
                .onToJson(function() {
                    console.log('event: onToJson', 'arguments:', arguments, 'context:', this);
                })
                .onSaveEditBoxInput(function() {
                    console.log('event: onSaveEditBoxInput', 'arguments:', arguments, 'context:', this);
                })
                .onItemDrag(function() {
                    console.log('event: onItemDrag', 'arguments:', arguments, 'context:', this);
                })
                .onItemDrop(function() {
                    console.log('event: onItemDrop', 'arguments:', arguments, 'context:', this);
                })
                .onItemAdded(function() {
                    console.log('event: onItemAdded', 'arguments:', arguments, 'context:', this);
                })
                .onItemRemoved(function() {
                    console.log('event: onItemRemoved', 'arguments:', arguments, 'context:', this);
                })
                .onItemStartEdit(function() {
                    console.log('event: onItemStartEdit', 'arguments:', arguments, 'context:', this);
                })
                .onItemEndEdit(function() {
                    console.log('event: onItemEndEdit', 'arguments:', arguments, 'context:', this);
                })
                .onItemAddChildItem(function() {
                    console.log('event: onItemAddChildItem', 'arguments:', arguments, 'context:', this);
                });
    });
    </script>


<?php
//$tree - menu data array
//$parent - 0
function get_menu($tree, $parent){
        $tree2 = array();
        foreach($tree as $i => $item){
            if($item['parent_id'] == $parent){
                $tree2[$item['id']] = $item;
                $tree2[$item['id']]['submenu'] = get_menu($tree, $item['id']);
            }
        }

        return $tree2;
    }
