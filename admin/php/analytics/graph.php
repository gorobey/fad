<?php
if($status !== AUTH_LOGGED){ die(); }

if(!isset($_GET['range'])){
	$range = 0;
}else{
	if($_GET['range']<0){
		$range = $_GET['range'];
	}else{
		$range = "+".$_GET['range'];
	}
}

$unique_access_data = mysqli_query($db_conn, "SELECT start_time, count(DAY(start_time)) as count 
FROM (SELECT start_time FROM ".$_CONFIG['t_analytics']." WHERE start_time >= (DATE_SUB(NOW(), INTERVAL ".$range." DAY) -interval 30 day) GROUP BY DAY(start_time), uid) as b GROUP BY DAY(start_time) ORDER BY MONTH(start_time) , DAY(start_time) ASC");

$page_view_data = mysqli_query($db_conn, "SELECT start_time, count(DAY(start_time)) as count 
FROM (SELECT start_time FROM ".$_CONFIG['t_analytics']." WHERE start_time >= (DATE_SUB(NOW(), INTERVAL ".$range." DAY) -interval 30 day) AND content != 0 ORDER BY MONTH(start_time) , DAY(start_time) ASC) as b GROUP BY DAY(start_time) ORDER BY MONTH(start_time) , DAY(start_time) ASC");

//range date
for($i=30;$i>=0;$i--){ $days[$i] = date("d/m/Y",(strtotime(-$i." day", strtotime($range." day")))); }
 
$month_days = "'".implode("','", $days)."'";

for($i=30;$i>=0;$i--){ $dates[date("d/m/Y",(strtotime(-$i." day", strtotime($range." day"))))] = 0; }
//override unique access per day
foreach($unique_access_data as $single_day){ $dates[date("d/m/Y", strtotime($single_day['start_time']))] = $single_day['count']; }
$unique_access = implode(", ", $dates);
//calculate max unique access
$max_unique_connections = max($dates);

unset($dates);
for($i=30;$i>=0;$i--){ $dates[date("d/m/Y",(strtotime(-$i." day", strtotime($range." day"))))] = 0; }
//override page view per day
foreach($page_view_data as $single_day){ $dates[date("d/m/Y", strtotime($single_day['start_time']))] = $single_day['count']; }
$page_view = implode(", ", $dates);
//calculate page view connection
$max_page_view = max($dates);

$max = array($max_unique_connections, $max_page_view);

?>
<style>
a.range:hover{text-decoration:none;}
.chart{
	height:300px;
}
.ct-label{
	font-size:.9em;
}
.ct-label.ct-horizontal{
/* Safari */
-webkit-transform: rotate(-30deg);

/* Firefox */
-moz-transform: rotate(-30deg);

/* IE */
-ms-transform: rotate(-30deg);

/* Opera */
-o-transform: rotate(-30deg);

/* Internet Explorer */
filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
	}
/*.ct-label.ct-horizontal:nth-child(-n+5){
	color:#333;
	}*/
.chartist-tooltip {
  position: absolute;
  display: inline-block;
  opacity: 0;
  min-width: 5em;
  padding: .5em;
  background: #f5f5f5;
  color: #333;
  text-align: center;
  pointer-events: none;
  z-index: 1;
  border:1px solid #ddd;
  border-radius:3px;
  -webkit-transition: opacity .2s linear;
  -moz-transition: opacity .2s linear;
  -o-transition: opacity .2s linear;
  transition: opacity .2s linear; }
  .chartist-tooltip:before {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    width: 0;
    height: 0;
    margin-left: -15px;
    border: 15px solid transparent;
    border-top-color: #ddd;}
  .chartist-tooltip.tooltip-show {
    opacity: .9; }

/*# sourceMappingURL=chartist-plugin-tooltip.css.map */
</style>
<div class="pull-right">
	<a href="php/dashboard.php?range=<?php echo $range-30;?>" class="ajax range fa fa-chevron-left"></a>
	<?php echo "  ".$days[30]." - ".$days[0]."  ";?>
	<a href="php/dashboard.php?range=<?php echo $range+30;?>" class="ajax range fa fa-chevron-right"></a>
</div>
<div class="chart ct-chart"></div>

<script src="../system/js/chartist.min.js"></script>
<script src="../system/js/chartist-plugin-tooltip.js"></script>

<script>
new Chartist.Line('.ct-chart', {
  labels: [<?php echo $month_days; ?>],
  series: [{
	  name: '<?php echo _("unique users");?>',
      data: [<?php echo $unique_access; ?>],
      },
    {
      name: '<?php echo _("Page view");?>',
      data: [<?php echo $page_view; ?>]
    }
  ]
}, {
  high: <?php echo max($max); ?>,
  low: 0,
  position: 'start',
  showArea: false,
  fullWidth: true,
  distributeSeries: true,
  plugins: [
    Chartist.plugins.tooltip()
  ],
  // As this is axis specific we need to tell Chartist to use whole numbers only on the concerned axis
  axisY: {
    onlyInteger: true
  },
    axisX: {
    // The offset of the labels to the chart area
    offset: 50,
    showGrid: false,
    // Position where labels are placed. Can be set to `start` or `end` where `start` is equivalent to left or top on vertical axis and `end` is equivalent to right or bottom on horizontal axis.
    position: 'end',
    // Allows you to correct label positioning on this axis by positive or negative x and y offset.
    labelOffset: {
      x: -45,
      y: 20
    }
   }
  
 
  
});
</script>
