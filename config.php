<?php
if (session_id() == '') {
    session_start();
}
//DEV
$_SESSION['DEV'] = FALSE;
$_CONFIG["MAINTENANCE_MODE"] = TRUE;

//DB CONNECTION CONFIG
define( 'DB_HOST', 'localhost' ); // set database host
define( 'DB_USER', '' ); // set database user
define( 'DB_NAME', '' ); // set database name
define( 'DB_PASS', '' ); // set database password

//DEFINITION TABLE PREFIX
$_CONFIG['table_prefix'] = "fad_";

//FOR SECURE MODULE INCLUDE
$_CONFIG['salt'] = "x_<VHI!Qj_HH:.Y{O(>AvB0=LVeu(.Zpt51+vU?Pj_o]=Mm&FwK=Y|;D$,YE-K>]";
$_CONFIG['pepper'] = "+.d+Nl+gsQ+:1+`5~t69U#HK})xQ4O4-kl[ur}[|WnJb@w-p^u~e5rlES-s/j,/d";

//LANGUAGE
$_SESSION['LANGUAGES']=array("en_US","it_IT");

//ENABLE / DISABLE REGITRATION
$_CONFIG['register']=TRUE;
$_CONFIG['primary_group']="subscriber"; #default "subscriber" (admin, subscriber ...other group have you created)

//USER LOGIN / COMFIRM TIME DEFINITION
$_CONFIG['expire'] = 60*60*24; //end session
$_CONFIG['regexpire'] = 168; //end confirm time

//ROLES FILES FOR UPLOAD
$_CONFIG['imgs_ext'] = array("jpg", "jpeg", "gif", "png");
$_CONFIG['video_ext'] = array("mp4", "mpg", "mpeg");
$_CONFIG['max_file_size'] = 52428800; //5MB

//FOR ENABLE GOOGLE ANALYTICS SERVICE PAST YOUR MONITORATION CODE IN '/analyticstracking.php' AND SAVE IT!

//DON'T EDIT AFTER THIS LINE!
// Defines some constants
define("ROOT", dirname(__file__));
define("ADMIN", ROOT."/admin/");
define("FRONTEND", ROOT."/frontend/");
define("SYSTEM", ROOT."/system/");
define("JS", SYSTEM."js/");
define("STYLE", SYSTEM."style/");

require_once(ROOT.'/system/config.php');

$server_name = str_replace("www.", "", $_SERVER['SERVER_NAME']);
$path_array = explode("/", $server_name.$_SERVER['PHP_SELF']);
array_pop($path_array);
define("HTTP", strpos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://');
define("ROOT_URL", implode("/", $path_array)."/");
