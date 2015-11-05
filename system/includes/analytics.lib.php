<?php
function get_analytics(){
	global $_CONFIG, $db_conn;
	$view_dataQ = mysqli_query($db_conn,
	"SELECT content, count(*) as count from ".$_CONFIG['t_analytics']." where content != 0 group by content");
	$data = array();
	while($tmp = mysqli_fetch_array($view_dataQ)){
		array_push($data, $tmp);
	}
	return $data;
}

function get_contets_count(){
	
}
