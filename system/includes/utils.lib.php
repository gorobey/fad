<?php
require_once(ROOT.'/translations/locale.php');

function menu($tree, $user_id){
	if($tree != "admin"){
		$path_repair = $tree."/";
	}else{
		$path_repair = "";		
	}
	$xml = simplexml_load_file(ROOT.'/system/structure.xml');
	foreach($xml->children() as $item){
		//print_r(get_user_attr($user_id, "r"));
		if($item->attributes()->tree == $tree){
			foreach($item as $first){
				echo '<li><a href="#"><i class="fa fa-'.$first->attributes()->icon.' fa-2x"></i> '.ucfirst(_($first->attributes()->name)).'<span class="fa expand-menu fa-minus-circle"></span></a>';
				if(count($first->children())>0){
					echo "<ul class='nav nav-second-level'>";
					foreach($first->children() as $second){
						if(!empty($second->attributes()->name)){
							$taxonomy = str_replace(" ", "-", $second->attributes()->name);
							echo '<li><a class="ajax fa fa-caret-right" href="'.protocol().ROOT_URL.$path_repair.'php/contents.php?level=1&type='.$taxonomy.'"> '.ucfirst(_($second->attributes()->name)) .'</a>';
							if(count($second->children())>0){
								echo '<ul class="nav nav-third-level">';
								foreach($second->children() as $third){
									$item = str_replace(" ", "-", $third->attributes()->name);
										echo '<li><a class="ajax fa fa-angle-right" href="'.protocol().ROOT_URL.$path_repair.'php/contents.php?level=2&type='.$item.'"> '.ucfirst(_($third->attributes()->name)) .'</a>';	
								}
								echo '</ul>';
							}
							echo '</li>';
						}
						echo "</ul>";
					}
				}//if $first->children()
				echo '</li>';
			}
		}
	}
}

function render_page($tree, $type, $user_id, $content_id = null){
	global $_CONFIG;
	$user_group = get_user_attr($user_id, "g");
	$user_roles = get_user_attr($user_id, "r");
	$xml = simplexml_load_file(ROOT.'/system/structure.xml');
	foreach($xml->children() as $node){ 
		if($node->attributes()->tree == $tree){
			foreach($node as $first){ echo $first;
				if(in_array($user_group, explode(",", $first->attributes()->group))){
					foreach($first->children() as $second){
						foreach($second->children() as $taxonomy){
							if($taxonomy->attributes()->name == $type){
								// print("<pre>".print_r($taxonomy->children(),true)."</pre>");
								foreach($taxonomy->children() as $modules){
									foreach($modules->attributes() as $key => $val){
										$module ="";
										if($key == "role"){
											$can_interact = count(array_intersect(explode(",", $val), $user_roles));
										}
										if($key == "script"){
											$module = ROOT.'/system/modules/'.$val.'/'.$tree.'.php';
											$JSmodule = ROOT."/system/modules/".$val."/js/";
											$CSSmodule = ROOT."/system/modules/".$val."/css/";
										}									
										if(($can_interact > 0 || is_admin($user_id) || ($key =="role" && $val=="")) && file_exists($module)){
											require($module);
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
}

function lang_menu($list = false) {
	global $_CONFIG;

	$current = "";

	foreach($_SESSION['LANGUAGES'] as $translation){
		$query = http_build_query(array_merge($_GET, array('lang' => $translation)));
		list($language, $domain) = explode("_", $translation);

		if($translation == $_SESSION[SESSION_LOCALE_KEY]){
			$current = "active";
		}else{
			$current = "";
		}
		if($list === true){//da fare nuova funzione
			$li = "<li><a class='fa fa-caret-right' ";$_li = "</li>";
		}elseif($list == "tab"){
			$li = "<li class='".$current."'><a class='ajax' ";$_li = "</li>";
		}else{
			$li = "<a ";
			if ($translation != end($_SESSION['LANGUAGES'])){
				$_li = ", ";
			}else{
				$_li = "";
			}
		}
		printf($li.'href="php/contents/edit_contents.php?%s">'.$_CONFIG['language_codes'][$language].'</a>'.$_li, $query);
	}
}

function content_lang_switch() {
	
}


//UTILS
function gen_rand_string() {
    $length = 10;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRSTUVWXYZ-_.,;)@(/?!';
    $maxrnd = strlen($characters)-1;
    $string = str_repeat('0', $length);
    for ($p = $length; $p--;) {
        $string[$p] = $characters[mt_rand(0, $maxrnd)];
    }
    return $string;
}
//SITE INFOS
function get_info($key){
	global $_CONFIG, $db_conn;
	$info_dataQ = mysqli_query($db_conn, "SELECT `value` FROM `".$_CONFIG['t_info']."` WHERE `".$_CONFIG['t_info']."`.`key`='".$key."'");
	$info_row = mysqli_fetch_assoc($info_dataQ);
	return $info_row['value'];
}

function protocol() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    return $protocol;
}

//USERS
function user_get_info($id){
	global $_CONFIG, $db_conn;
	$result = mysqli_query($db_conn, "
	SELECT id, name, surname, uid, mail, regdate FROM ".$_CONFIG['t_users']." WHERE id='".$id."'");
	$data = mysqli_fetch_assoc($result);
	return $data;
}

function user_get_email($id){
	global $_CONFIG, $db_conn;
	$user_dataQ = mysqli_query($db_conn, "SELECT mail FROM ".$_CONFIG['t_users']." WHERE id='".$id."'");
	$user_row = mysqli_fetch_assoc($user_dataQ);
	return $user_row['mail'];
}

function user_from_email($email){
	global $_CONFIG, $db_conn;
	$user_dataQ = mysqli_query($db_conn, "SELECT id FROM ".$_CONFIG['t_users']." WHERE mail='".$email."'");
	$user_row = mysqli_fetch_assoc($user_dataQ);
	return user_get_info($user_row['id']);
}

//users list
function get_users_list(){
	global $_CONFIG, $db_conn;
	$result = mysqli_query($db_conn, "
	SELECT id, name, surname, mail, temp
	FROM ".$_CONFIG['t_users']."
	");
	$data = array();
	while($tmp = mysqli_fetch_array($result)){
		array_push($data, $tmp);
	}
	return $data;
}

//lists group and roles
function get_user_attr($user_id, $attr = "g"){ //$attr = g per gruppo, r per ruolo
	global $_CONFIG, $db_conn;
	if($attr == "g"){
		$table_with_join = $_CONFIG['t_groups'];
		$limit = " LIMIT 1";
	}elseif($attr == "r"){
		$table_with_join = $_CONFIG['t_roles'];
		$limit = "";
	}else{
		exit;
	}

	$result = mysqli_query($db_conn, "
	SELECT
	DISTINCT(".$table_with_join.".name)
	FROM ".$_CONFIG['t_attr']."
	INNER JOIN ".$table_with_join."
	WHERE
	".$_CONFIG['t_attr'].".id = ".$user_id."
	AND
	".$_CONFIG['t_attr'].".type = '".$attr."'
	AND
	".$_CONFIG['t_attr'].".value = ".$table_with_join.".id
	".$limit);

	if($attr == "g"){
		$data = mysqli_fetch_array($result);
		return $data['name'];
	}

	if($attr == "r"){
		$data = array();
		while($tmp = mysqli_fetch_array($result)){
			array_push($data, $tmp['name']);
		}
		return $data;
	}
}

function get_groups_list(){
	global $_CONFIG, $db_conn;
	$result = mysqli_query($db_conn, "
	SELECT id, name
	FROM ".$_CONFIG['t_groups']."
	");
	$data = array();
	while($tmp = mysqli_fetch_array($result)){
		array_push($data, $tmp);
	}
	
	return $data;
}

function get_roles_list(){
	global $_CONFIG, $db_conn;
	$result = mysqli_query($db_conn, "
	SELECT id, name
	FROM ".$_CONFIG['t_roles']."
	");
	$data = array();
	while($tmp = mysqli_fetch_array($result)){
		array_push($data, $tmp);
	}
	
	return $data;
}

function get_users_count(){
	global $_CONFIG, $db_conn;
	$result = mysqli_query($db_conn, "
	SELECT id
	FROM ".$_CONFIG['t_users']."
	WHERE temp='0'
	");
	$count = mysqli_num_rows($result);
	return $count;
}

function get_users_online(){
	global $_CONFIG, $db_conn;
	$result = mysqli_query($db_conn, "
	SELECT distinct(user_id)
	FROM ".$_CONFIG['t_session']);
	$count = mysqli_num_rows($result);
	return $count;
}

function is_online($id){
	global $_CONFIG, $db_conn;
	$result = mysqli_query($db_conn, "SELECT user_id FROM ".$_CONFIG['t_session']. " WHERE user_id=".$id." AND ".time()."- UNIX_TIMESTAMP(creation_date) < 60");
	$count = mysqli_num_rows($result);
	if($count==1){
		$status = "<i class='useronline'></i>"; 
	}else{
		$status = "<i class='useroffline'></i>";
	}
	return $status;
}

function primary_group(){
	global $_CONFIG, $db_conn;
	$result = mysqli_query($db_conn, "SELECT id FROM ".$_CONFIG['t_groups']." WHERE name = '".$_CONFIG['primary_group']."'");
	$primary_group = mysqli_fetch_assoc($result);
	return $primary_group['id'];
}

//FOR HUMAN
function get_real_name($id){
	global $_CONFIG, $db_conn;
	$result = mysqli_query($db_conn, "SELECT name, surname FROM ".$_CONFIG['t_users']." WHERE id = '".$id."'");
		$real = mysqli_fetch_assoc($result);
		return trim(ucfirst($real['name']).' '.ucfirst($real['surname']));
}

function profile_image($id){
	global $_CONFIG, $db_conn;
	$result = mysqli_query($db_conn, "SELECT image FROM ".$_CONFIG['t_users']." WHERE id = '".$id."'");
	$image = mysqli_fetch_assoc($result);
	if (!empty($image['image'])) {
		$img = $image['image'];
	}else{
		$img = "";
	}
	return $img;
}

function profile_img($id, $type="list") {
	$resize=protocol().get_info('appdir')."system/includes/resize.lib.php";
	$image = profile_image($id);
	$human_name = get_real_name($id);
	$link='<a href="'.protocol().get_info('appdir').'user/?id='.$id.'" class="ajax user_link" rel="'.$id.'">';
	$linkc='</a>';
	if(is_numeric($type)){
		$size=$type;
		$style="";
		$resize.="?w=".$type."&h=".$type."&img=";
	}elseif($type=="list"){
		$size=38;
		$resize.="?w=".$size."&h=".$size."&img=";
		$style='style="float:left;margin:1px;width:'.$size.'px;height:'.$size.'px;"';
	}elseif($type=="clear"){
		$size=150;
		$resize.="?w=".$size."&h=".$size."&img=";
		$style='style="width:'.$size.'px;height:'.$size.'px;"';
	}
	if($image==""){
		$url = 'http://www.gravatar.com/avatar/';
		$url .= md5( strtolower( trim( user_get_email($id) ) ) );
		$url .= "?s=".$size."&d=retro";//?s=$s&d=$d&r=$r
		$atts = array();
		$avatar = '<img class="user-image img-responsive" '.$style.' width="'.$size.'" height="'.$size.'" alt="'.$human_name.'" title="'.$human_name.'" src="' . $url . '"';
		foreach ( $atts as $key => $val ){
			$avatar .= ' ' . $key . '="' . $val . '"';
		}
		$avatar .= ' />';
	}else{
		$avatar = '<img class="user-image img-responsive" '.$style.' alt="'.$human_name.'" title="'.$human_name.' " src="'.$resize.'../../user/profiles/'.$image.'" />';
	}
	$img = $link.$avatar.$linkc;
	return $img;
}

function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    // Uncomment one of the following alternatives
    // $bytes /= pow(1024, $pow);
     $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
}


//CONTENTS
function get_contents($type, $lang=''){ //da riparare!!
global $_CONFIG, $db_conn;
$query = "SELECT 
".$_CONFIG['t_content'].".id,
".$_CONFIG['t_content'].".author,
".$_CONFIG['t_content'].".publish,
".$_CONFIG['t_content'].".date,
".$_CONFIG['t_content'].".type,
".$_CONFIG['t_blocks'].".id,
".$_CONFIG['t_blocks'].".order,
".$_CONFIG['t_blocks'].".key,
".$_CONFIG['t_blocks'].".value
FROM ".$_CONFIG['t_content']."
INNER JOIN ".$_CONFIG['t_blocks']."
WHERE ".$_CONFIG['t_content'].".id = ".$_CONFIG['t_blocks'].".rel
AND ".$_CONFIG['t_content'].".type='".$type."'";

if($lang!=''){$query .= " AND ".$_CONFIG['t_blocks'].".lang = '".$lang."'";}

$query .= " ORDER BY ".$_CONFIG['t_blocks'].".order";

$result = mysqli_query($db_conn, $query);

$data = array();

while($tmp = mysqli_fetch_array($result)){
	array_push($data, $tmp);
}

return $data;

}

function get_tags($content=''){
	
}

function get_category($content=''){
	
}



//TIME
function sec2hms ($sec, $padHours = false) {
	$hms = "";
	$hours = intval(intval($sec) / 3600); 
	$hms .= ($padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT). ":" : $hours. ":";
	$minutes = intval(($sec / 60) % 60); 
	$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ":";
	$seconds = intval($sec % 60); 
	$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
	return $hms;
}

function RelativeTime($ts){//da usare nel blog
	if(!ctype_digit($ts)){
		$ts = strtotime($ts);
	}
	$diff = time() - $ts;
	if($diff == 0){
		return 'now';
	}
	elseif($diff > 0){
		$day_diff = floor($diff / 86400);
	if($day_diff == 0)
	{
	    if($diff < 60) return _('just now');
	    if($diff < 120) return '1 minute ago';
	    if($diff < 3600) return floor($diff / 60) . _(' minutes ago');
	    if($diff < 7200) return _('1 hour ago');
	    if($diff < 86400) return floor($diff / 3600) . _(' hours ago');
	}
	if($day_diff == 1) return _('Yesterday');
	if($day_diff < 7) return $day_diff . _(' days ago');
	if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
	if($day_diff < 60) return _('last month');
	return date('F Y', $ts);
    }
    else
    {
	$diff = abs($diff);
	$day_diff = floor($diff / 86400);
	if($day_diff == 0)
	{
	    if($diff < 120) return _('in a minute');
	    if($diff < 3600) return 'in ' . floor($diff / 60) . _(' minutes');
	    if($diff < 7200) return 'in an hour';
	    if($diff < 86400) return 'in ' . floor($diff / 3600) . _(' hours');
	}
	if($day_diff == 1) return 'Tomorrow';
	if($day_diff < 4) return date('l', $ts);
	if($day_diff < 7 + (7 - date('w'))) return 'next week';
	if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . _(' weeks');
	if(date('n', $ts) == date('n') + 1) return _('next month');
	return date('F Y', $ts);
    }
}

function mail_exists($mail){
	global $_CONFIG, $db_conn;
	$mail_exists = mysqli_num_rows(mysqli_query($db_conn, "
	SELECT mail
	FROM ".$_CONFIG['t_users']."
	WHERE mail='".mysqli_real_escape_string($db_conn, $mail)."'
	"));
	return $mail_exists;
}

//MEDIA FUNCTIONS
function is_dir_empty($dir) {
  if (!is_readable($dir) && !is_dir($dir)) return NULL; 
  $handle = @opendir($dir);
  while (false !== ($entry = @readdir($handle))) {
    if ($entry != "." && $entry != ".." && $entry != ".htaccess") {
      return FALSE;
    }
  }
  return TRUE;
}
function mime_filter ($mime){//not used
	$mime_types = array( '.3dm' => 'x-world/x-3dmf', '.3dmf' => 'x-world/x-3dmf', '.a' => 'application/octet-stream', '.aab' => 'application/x-authorware-bin', '.aam' => 'application/x-authorware-map', '.aas' => 'application/x-authorware-seg', '.abc' => 'text/vnd.abc', '.acgi' => 'text/html', '.afl' => 'video/animaflex', '.ai' => 'application/postscript', '.aif' => 'audio/aiff', '.aif' => 'audio/x-aiff', '.aifc' => 'audio/aiff', '.aifc' => 'audio/x-aiff', '.aiff' => 'audio/aiff', '.aiff' => 'audio/x-aiff', '.aim' => 'application/x-aim', '.aip' => 'text/x-audiosoft-intra', '.ani' => 'application/x-navi-animation', '.aos' => 'application/x-nokia-9000-communicator-add-on-software', '.aps' => 'application/mime', '.arc' => 'application/octet-stream', '.arj' => 'application/arj', '.arj' => 'application/octet-stream', '.art' => 'image/x-jg', '.asf' => 'video/x-ms-asf', '.asm' => 'text/x-asm', '.asp' => 'text/asp', '.asx' => 'application/x-mplayer2', '.asx' => 'video/x-ms-asf', '.asx' => 'video/x-ms-asf-plugin', '.au' => 'audio/basic', '.au' => 'audio/x-au', '.avi' => 'application/x-troff-msvideo', '.avi' => 'video/avi', '.avi' => 'video/msvideo', '.avi' => 'video/x-msvideo', '.avs' => 'video/avs-video', '.bcpio' => 'application/x-bcpio', '.bin' => 'application/mac-binary', '.bin' => 'application/macbinary', '.bin' => 'application/octet-stream', '.bin' => 'application/x-binary', '.bin' => 'application/x-macbinary', '.bm' => 'image/bmp', '.bmp' => 'image/bmp', '.bmp' => 'image/x-windows-bmp', '.boo' => 'application/book', '.book' => 'application/book', '.boz' => 'application/x-bzip2', '.bsh' => 'application/x-bsh', '.bz' => 'application/x-bzip', '.bz2' => 'application/x-bzip2', '.c' => 'text/plain', '.c++' => 'text/plain', '.cat' => 'application/vnd.ms-pki.seccat', '.cc' => 'text/plain', '.cc' => 'text/x-c', '.ccad' => 'application/clariscad', '.cco' => 'application/x-cocoa', '.cdf' => 'application/cdf', '.cdf' => 'application/x-cdf', '.cdf' => 'application/x-netcdf', '.cer' => 'application/pkix-cert', '.cer' => 'application/x-x509-ca-cert', '.cha' => 'application/x-chat', '.chat' => 'application/x-chat', '.class' => 'application/java', '.class' => 'application/java-byte-code', '.class' => 'application/x-java-class', '.com' => 'application/octet-stream', '.com' => 'text/plain', '.conf' => 'text/plain', '.cpio' => 'application/x-cpio', '.cpp' => 'text/x-c', '.cpt' => 'application/mac-compactpro', '.cpt' => 'application/x-compactpro', '.cpt' => 'application/x-cpt', '.crl' => 'application/pkcs-crl', '.crl' => 'application/pkix-crl', '.crt' => 'application/pkix-cert', '.crt' => 'application/x-x509-ca-cert', '.crt' => 'application/x-x509-user-cert', '.csh' => 'application/x-csh', '.csh' => 'text/x-script.csh', '.css' => 'application/x-pointplus', '.css' => 'text/css', '.cxx' => 'text/plain', '.dcr' => 'application/x-director', '.deepv' => 'application/x-deepv', '.def' => 'text/plain', '.der' => 'application/x-x509-ca-cert', '.dif' => 'video/x-dv', '.dir' => 'application/x-director', '.dl' => 'video/dl', '.dl' => 'video/x-dl', '.doc' => 'application/msword', '.dot' => 'application/msword', '.dp' => 'application/commonground', '.drw' => 'application/drafting', '.dump' => 'application/octet-stream', '.dv' => 'video/x-dv', '.dvi' => 'application/x-dvi', '.dwf' => 'drawing/x-dwf (old)', '.dwf' => 'model/vnd.dwf', '.dwg' => 'application/acad', '.dwg' => 'image/vnd.dwg', '.dwg' => 'image/x-dwg', '.dxf' => 'application/dxf', '.dxf' => 'image/vnd.dwg', '.dxf' => 'image/x-dwg', '.dxr' => 'application/x-director', '.el' => 'text/x-script.elisp', '.elc' => 'application/x-bytecode.elisp (compiled elisp)', '.elc' => 'application/x-elc', '.env' => 'application/x-envoy', '.eps' => 'application/postscript', '.es' => 'application/x-esrehber', '.etx' => 'text/x-setext', '.evy' => 'application/envoy', '.evy' => 'application/x-envoy', '.exe' => 'application/octet-stream', '.f' => 'text/plain', '.f' => 'text/x-fortran', '.f77' => 'text/x-fortran', '.f90' => 'text/plain', '.f90' => 'text/x-fortran', '.fdf' => 'application/vnd.fdf', '.fif' => 'application/fractals', '.fif' => 'image/fif', '.fli' => 'video/fli', '.fli' => 'video/x-fli', '.flo' => 'image/florian', '.flx' => 'text/vnd.fmi.flexstor', '.fmf' => 'video/x-atomic3d-feature', '.for' => 'text/plain', '.for' => 'text/x-fortran', '.fpx' => 'image/vnd.fpx', '.fpx' => 'image/vnd.net-fpx', '.frl' => 'application/freeloader', '.funk' => 'audio/make', '.g' => 'text/plain', '.g3' => 'image/g3fax', '.gif' => 'image/gif', '.gl' => 'video/gl', '.gl' => 'video/x-gl', '.gsd' => 'audio/x-gsm', '.gsm' => 'audio/x-gsm', '.gsp' => 'application/x-gsp', '.gss' => 'application/x-gss', '.gtar' => 'application/x-gtar', '.gz' => 'application/x-compressed', '.gz' => 'application/x-gzip', '.gzip' => 'application/x-gzip', '.gzip' => 'multipart/x-gzip', '.h' => 'text/plain', '.h' => 'text/x-h', '.hdf' => 'application/x-hdf', '.help' => 'application/x-helpfile', '.hgl' => 'application/vnd.hp-hpgl', '.hh' => 'text/plain', '.hh' => 'text/x-h', '.hlb' => 'text/x-script', '.hlp' => 'application/hlp', '.hlp' => 'application/x-helpfile', '.hlp' => 'application/x-winhelp', '.hpg' => 'application/vnd.hp-hpgl', '.hpgl' => 'application/vnd.hp-hpgl', '.hqx' => 'application/binhex', '.hqx' => 'application/binhex4', '.hqx' => 'application/mac-binhex', '.hqx' => 'application/mac-binhex40', '.hqx' => 'application/x-binhex40', '.hqx' => 'application/x-mac-binhex40', '.hta' => 'application/hta', '.htc' => 'text/x-component', '.htm' => 'text/html', '.html' => 'text/html', '.htmls' => 'text/html', '.htt' => 'text/webviewhtml', '.htx' => 'text/html', '.ice' => 'x-conference/x-cooltalk', '.ico' => 'image/x-icon', '.idc' => 'text/plain', '.ief' => 'image/ief', '.iefs' => 'image/ief', '.iges' => 'application/iges', '.iges' => 'model/iges', '.igs' => 'application/iges', '.igs' => 'model/iges', '.ima' => 'application/x-ima', '.imap' => 'application/x-httpd-imap', '.inf' => 'application/inf', '.ins' => 'application/x-internett-signup', '.ip' => 'application/x-ip2', '.isu' => 'video/x-isvideo', '.it' => 'audio/it', '.iv' => 'application/x-inventor', '.ivr' => 'i-world/i-vrml', '.ivy' => 'application/x-livescreen', '.jam' => 'audio/x-jam', '.jav' => 'text/plain', '.jav' => 'text/x-java-source', '.java' => 'text/plain', '.java' => 'text/x-java-source', '.jcm' => 'application/x-java-commerce', '.jfif' => 'image/jpeg', '.jfif' => 'image/pjpeg', '.jfif-tbnl' => 'image/jpeg', '.jpe' => 'image/jpeg', '.jpe' => 'image/pjpeg', '.jpeg' => 'image/jpeg', '.jpeg' => 'image/pjpeg', '.jpg' => 'image/jpeg', '.jpg' => 'image/pjpeg', '.jps' => 'image/x-jps', '.js' => 'application/x-javascript', '.jut' => 'image/jutvision', '.kar' => 'audio/midi', '.kar' => 'music/x-karaoke', '.ksh' => 'application/x-ksh', '.ksh' => 'text/x-script.ksh', '.la' => 'audio/nspaudio', '.la' => 'audio/x-nspaudio', '.lam' => 'audio/x-liveaudio', '.latex' => 'application/x-latex', '.lha' => 'application/lha', '.lha' => 'application/octet-stream', '.lha' => 'application/x-lha', '.lhx' => 'application/octet-stream', '.list' => 'text/plain', '.lma' => 'audio/nspaudio', '.lma' => 'audio/x-nspaudio', '.log' => 'text/plain', '.lsp' => 'application/x-lisp', '.lsp' => 'text/x-script.lisp', '.lst' => 'text/plain', '.lsx' => 'text/x-la-asf', '.ltx' => 'application/x-latex', '.lzh' => 'application/octet-stream', '.lzh' => 'application/x-lzh', '.lzx' => 'application/lzx', '.lzx' => 'application/octet-stream', '.lzx' => 'application/x-lzx', '.m' => 'text/plain', '.m' => 'text/x-m', '.m1v' => 'video/mpeg', '.m2a' => 'audio/mpeg', '.m2v' => 'video/mpeg', '.m3u' => 'audio/x-mpequrl', '.man' => 'application/x-troff-man', '.map' => 'application/x-navimap', '.mar' => 'text/plain', '.mbd' => 'application/mbedlet', '.mc' => 'application/x-magic-cap-package-1.0', '.mcd' => 'application/mcad', '.mcd' => 'application/x-mathcad', '.mcf' => 'image/vasa', '.mcf' => 'text/mcf', '.mcp' => 'application/netmc', '.me' => 'application/x-troff-me', '.mht' => 'message/rfc822', '.mhtml' => 'message/rfc822', '.mid' => 'application/x-midi', '.mid' => 'audio/midi', '.mid' => 'audio/x-mid', '.mid' => 'audio/x-midi', '.mid' => 'music/crescendo', '.mid' => 'x-music/x-midi', '.midi' => 'application/x-midi', '.midi' => 'audio/midi', '.midi' => 'audio/x-mid', '.midi' => 'audio/x-midi', '.midi' => 'music/crescendo', '.midi' => 'x-music/x-midi', '.mif' => 'application/x-frame', '.mif' => 'application/x-mif', '.mime' => 'message/rfc822', '.mime' => 'www/mime', '.mjf' => 'audio/x-vnd.audioexplosion.mjuicemediafile', '.mjpg' => 'video/x-motion-jpeg', '.mm' => 'application/base64', '.mm' => 'application/x-meme', '.mme' => 'application/base64', '.mod' => 'audio/mod', '.mod' => 'audio/x-mod', '.moov' => 'video/quicktime', '.mov' => 'video/quicktime', '.movie' => 'video/x-sgi-movie', '.mp2' => 'audio/mpeg', '.mp2' => 'audio/x-mpeg', '.mp2' => 'video/mpeg', '.mp2' => 'video/x-mpeg', '.mp2' => 'video/x-mpeq2a', '.mp3' => 'audio/mpeg3', '.mp3' => 'audio/x-mpeg-3', '.mp3' => 'video/mpeg', '.mp3' => 'video/x-mpeg', '.mpa' => 'audio/mpeg', '.mpa' => 'video/mpeg', '.mpc' => 'application/x-project', '.mpe' => 'video/mpeg', '.mpeg' => 'video/mpeg', '.mpg' => 'audio/mpeg', '.mpg' => 'video/mpeg', '.mpga' => 'audio/mpeg', '.mpp' => 'application/vnd.ms-project', '.mpt' => 'application/x-project', '.mpv' => 'application/x-project', '.mpx' => 'application/x-project', '.mrc' => 'application/marc', '.ms' => 'application/x-troff-ms', '.mv' => 'video/x-sgi-movie', '.my' => 'audio/make', '.mzz' => 'application/x-vnd.audioexplosion.mzz', '.nap' => 'image/naplps', '.naplps' => 'image/naplps', '.nc' => 'application/x-netcdf', '.ncm' => 'application/vnd.nokia.configuration-message', '.nif' => 'image/x-niff', '.niff' => 'image/x-niff', '.nix' => 'application/x-mix-transfer', '.nsc' => 'application/x-conference', '.nvd' => 'application/x-navidoc', '.o' => 'application/octet-stream', '.oda' => 'application/oda', '.omc' => 'application/x-omc', '.omcd' => 'application/x-omcdatamaker', '.omcr' => 'application/x-omcregerator', '.p' => 'text/x-pascal', '.p10' => 'application/pkcs10', '.p10' => 'application/x-pkcs10', '.p12' => 'application/pkcs-12', '.p12' => 'application/x-pkcs12', '.p7a' => 'application/x-pkcs7-signature', '.p7c' => 'application/pkcs7-mime', '.p7c' => 'application/x-pkcs7-mime', '.p7m' => 'application/pkcs7-mime', '.p7m' => 'application/x-pkcs7-mime', '.p7r' => 'application/x-pkcs7-certreqresp', '.p7s' => 'application/pkcs7-signature', '.part' => 'application/pro_eng', '.pas' => 'text/pascal', '.pbm' => 'image/x-portable-bitmap', '.pcl' => 'application/vnd.hp-pcl', '.pcl' => 'application/x-pcl', '.pct' => 'image/x-pict', '.pcx' => 'image/x-pcx', '.pdb' => 'chemical/x-pdb', '.pdf' => 'application/pdf', '.pfunk' => 'audio/make', '.pgm' => 'image/x-portable-greymap', '.pic' => 'image/pict', '.pict' => 'image/pict', '.pkg' => 'application/x-newton-compatible-pkg', '.pko' => 'application/vnd.ms-pki.pko', '.pl' => 'text/plain', '.pl' => 'text/x-script.perl', '.plx' => 'application/x-pixclscript', '.pm' => 'image/x-xpixmap', '.pm' => 'text/x-script.perl-module', '.pm4' => 'application/x-pagemaker', '.pm5' => 'application/x-pagemaker', '.png' => 'image/png', '.pnm' => 'application/x-portable-anymap', '.pnm' => 'image/x-portable-anymap', '.pot' => 'application/mspowerpoint', '.pot' => 'application/vnd.ms-powerpoint', '.pov' => 'model/x-pov', '.ppa' => 'application/vnd.ms-powerpoint', '.ppm' => 'image/x-portable-pixmap', '.pps' => 'application/mspowerpoint', '.pps' => 'application/vnd.ms-powerpoint', '.ppt' => 'application/mspowerpoint', '.ppt' => 'application/powerpoint', '.ppt' => 'application/vnd.ms-powerpoint', '.ppt' => 'application/x-mspowerpoint', '.ppz' => 'application/mspowerpoint', '.pre' => 'application/x-freelance', '.prt' => 'application/pro_eng', '.ps' => 'application/postscript', '.psd' => 'application/octet-stream', '.pvu' => 'paleovu/x-pv', '.pwz' => 'application/vnd.ms-powerpoint', '.py' => 'text/x-script.phyton', '.pyc' => 'applicaiton/x-bytecode.python', '.qcp' => 'audio/vnd.qcelp', '.qd3' => 'x-world/x-3dmf', '.qd3d' => 'x-world/x-3dmf', '.qif' => 'image/x-quicktime', '.qt' => 'video/quicktime', '.qtc' => 'video/x-qtc', '.qti' => 'image/x-quicktime', '.qtif' => 'image/x-quicktime', '.ra' => 'audio/x-pn-realaudio', '.ra' => 'audio/x-pn-realaudio-plugin', '.ra' => 'audio/x-realaudio', '.ram' => 'audio/x-pn-realaudio', '.ras' => 'application/x-cmu-raster', '.ras' => 'image/cmu-raster', '.ras' => 'image/x-cmu-raster', '.rast' => 'image/cmu-raster', '.rexx' => 'text/x-script.rexx', '.rf' => 'image/vnd.rn-realflash', '.rgb' => 'image/x-rgb', '.rm' => 'application/vnd.rn-realmedia', '.rm' => 'audio/x-pn-realaudio', '.rmi' => 'audio/mid', '.rmm' => 'audio/x-pn-realaudio', '.rmp' => 'audio/x-pn-realaudio', '.rmp' => 'audio/x-pn-realaudio-plugin', '.rng' => 'application/ringing-tones', '.rng' => 'application/vnd.nokia.ringing-tone', '.rnx' => 'application/vnd.rn-realplayer', '.roff' => 'application/x-troff', '.rp' => 'image/vnd.rn-realpix', '.rpm' => 'audio/x-pn-realaudio-plugin', '.rt' => 'text/richtext', '.rt' => 'text/vnd.rn-realtext', '.rtf' => 'application/rtf', '.rtf' => 'application/x-rtf', '.rtf' => 'text/richtext', '.rtx' => 'application/rtf', '.rtx' => 'text/richtext', '.rv' => 'video/vnd.rn-realvideo', '.s' => 'text/x-asm', '.s3m' => 'audio/s3m', '.saveme' => 'aapplication/octet-stream', '.sbk' => 'application/x-tbook', '.scm' => 'application/x-lotusscreencam', '.scm' => 'text/x-script.guile', '.scm' => 'text/x-script.scheme', '.scm' => 'video/x-scm', '.sdml' => 'text/plain', '.sdp' => 'application/sdp', '.sdp' => 'application/x-sdp', '.sdr' => 'application/sounder', '.sea' => 'application/sea', '.sea' => 'application/x-sea', '.set' => 'application/set', '.sgm' => 'text/sgml', '.sgm' => 'text/x-sgml', '.sgml' => 'text/sgml', '.sgml' => 'text/x-sgml', '.sh' => 'application/x-bsh', '.sh' => 'application/x-sh', '.sh' => 'application/x-shar', '.sh' => 'text/x-script.sh', '.shar' => 'application/x-bsh', '.shar' => 'application/x-shar', '.shtml' => 'text/html', '.shtml' => 'text/x-server-parsed-html', '.sid' => 'audio/x-psid', '.sit' => 'application/x-sit', '.sit' => 'application/x-stuffit', '.skd' => 'application/x-koan', '.skm' => 'application/x-koan', '.skp' => 'application/x-koan', '.skt' => 'application/x-koan', '.sl' => 'application/x-seelogo', '.smi' => 'application/smil', '.smil' => 'application/smil', '.snd' => 'audio/basic', '.snd' => 'audio/x-adpcm', '.sol' => 'application/solids', '.spc' => 'application/x-pkcs7-certificates', '.spc' => 'text/x-speech', '.spl' => 'application/futuresplash', '.spr' => 'application/x-sprite', '.sprite' => 'application/x-sprite', '.src' => 'application/x-wais-source', '.ssi' => 'text/x-server-parsed-html', '.ssm' => 'application/streamingmedia', '.sst' => 'application/vnd.ms-pki.certstore', '.step' => 'application/step', '.stl' => 'application/sla', '.stl' => 'application/vnd.ms-pki.stl', '.stl' => 'application/x-navistyle', '.stp' => 'application/step', '.sv4cpio' =>'application/x-sv4cpio', '.sv4crc' => 'application/x-sv4crc', '.svf' => 'image/vnd.dwg', '.svf' => 'image/x-dwg', '.svr' => 'application/x-world', '.svr' => 'x-world/x-svr', '.swf' => 'application/x-shockwave-flash', '.t' => 'application/x-troff', '.talk' => 'text/x-speech', '.tar' => 'application/x-tar', '.tbk' => 'application/toolbook', '.tbk' => 'application/x-tbook', '.tcl' => 'application/x-tcl', '.tcl' => 'text/x-script.tcl', '.tcsh' => 'text/x-script.tcsh', '.tex' => 'application/x-tex', '.texi' => 'application/x-texinfo', '.texinfo' =>' lication/x-texinfo', '.text' => 'application/plain', '.text' => 'text/plain', '.tgz' => 'application/gnutar', '.tgz' => 'application/x-compressed', '.tif' => 'image/tiff', '.tif' => 'image/x-tiff', '.tiff' => 'image/tiff', '.tiff' => 'image/x-tiff', '.tr' => 'application/x-troff', '.tsi' => 'audio/tsp-audio', '.tsp' => 'application/dsptype', '.tsp' => 'audio/tsplayer', '.tsv' => 'text/tab-separated-values', '.turbot' => 'image/florian', '.txt' => 'text/plain', '.uil' => 'text/x-uil', '.uni' => 'text/uri-list', '.unis' => 'text/uri-list', '.unv' => 'application/i-deas', '.uri' => 'text/uri-list', '.uris' => 'text/uri-list', '.ustar' => 'application/x-ustar', '.ustar' => 'multipart/x-ustar', '.uu' => 'application/octet-stream', '.uu' => 'text/x-uuencode', '.uue' => 'text/x-uuencode', '.vcd' => 'application/x-cdlink', '.vcs' => 'text/x-vcalendar', '.vda' => 'application/vda', '.vdo' => 'video/vdo', '.vew' => 'application/groupwise', '.viv' => 'video/vivo', '.viv' => 'video/vnd.vivo', '.vivo' => 'video/vivo', '.vivo' => 'video/vnd.vivo', '.vmd' => 'application/vocaltec-media-desc', '.vmf' => 'application/vocaltec-media-file', '.voc' => 'audio/voc', '.voc' => 'audio/x-voc', '.vos' => 'video/vosaic', '.vox' => 'audio/voxware', '.vqe' => 'audio/x-twinvq-plugin', '.vqf' => 'audio/x-twinvq', '.vql' => 'audio/x-twinvq-plugin', '.vrml' => 'application/x-vrml', '.vrml' => 'model/vrml', '.vrml' => 'x-world/x-vrml', '.vrt' => 'x-world/x-vrt', '.vsd' => 'application/x-visio', '.vst' => 'application/x-visio', '.vsw' => 'application/x-visio', '.w60' => 'application/wordperfect6.0', '.w61' => 'application/wordperfect6.1', '.w6w' => 'application/msword', '.wav' => 'audio/wav', '.wav' => 'audio/x-wav', '.wb1' => 'application/x-qpro', '.wbmp' => 'image/vnd.wap.wbmp', '.web' => 'application/vnd.xara', '.wiz' => 'application/msword', '.wk1' => 'application/x-123', '.wmf' => 'windows/metafile', '.wml' => 'text/vnd.wap.wml', '.wmlc' => 'application/vnd.wap.wmlc', '.wmls' => 'text/vnd.wap.wmlscript', '.wmlsc' => 'application/vnd.wap.wmlscriptc', '.word' => 'application/msword', '.wp' => 'application/wordperfect', '.wp5' => 'application/wordperfect', '.wp5' => 'application/wordperfect6.0', '.wp6' => 'application/wordperfect', '.wpd' => 'application/wordperfect', '.wpd' => 'application/x-wpwin', '.wq1' => 'application/x-lotus', '.wri' => 'application/mswrite', '.wri' => 'application/x-wri', '.wrl' => 'application/x-world', '.wrl' => 'model/vrml', '.wrl' => 'x-world/x-vrml', '.wrz' => 'model/vrml', '.wrz' => 'x-world/x-vrml', '.wsc' => 'text/scriplet', '.wsrc' => 'application/x-wais-source', '.wtk' => 'application/x-wintalk', '.xbm' => 'image/x-xbitmap', '.xbm' => 'image/x-xbm', '.xbm' => 'image/xbm', '.xdr' => 'video/x-amt-demorun', '.xgz' => 'xgl/drawing', '.xif' => 'image/vnd.xiff', '.xl' => 'application/excel', '.xla' => 'application/excel', '.xla' => 'application/x-excel', '.xla' => 'application/x-msexcel', '.xlb' => 'application/excel', '.xlb' => 'application/vnd.ms-excel', '.xlb' => 'application/x-excel', '.xlc' => 'application/excel', '.xlc' => 'application/vnd.ms-excel', '.xlc' => 'application/x-excel', '.xld' => 'application/excel', '.xld' => 'application/x-excel', '.xlk' => 'application/excel', '.xlk' => 'application/x-excel', '.xll' => 'application/excel', '.xll' => 'application/vnd.ms-excel', '.xll' => 'application/x-excel', '.xlm' => 'application/excel', '.xlm' => 'application/vnd.ms-excel', '.xlm' => 'application/x-excel', '.xls' => 'application/excel', '.xls' => 'application/vnd.ms-excel', '.xls' => 'application/x-excel', '.xls' => 'application/x-msexcel', '.xlt' => 'application/excel', '.xlt' => 'application/x-excel', '.xlv' => 'application/excel', '.xlv' => 'application/x-excel', '.xlw' => 'application/excel', '.xlw' => 'application/vnd.ms-excel', '.xlw' => 'application/x-excel', '.xlw' => 'application/x-msexcel', '.xm' => 'audio/xm', '.xml' => 'application/xml', '.xml' => 'text/xml', '.xmz' => 'xgl/movie', '.xpix' => 'application/x-vnd.ls-xpix', '.xpm' => 'image/x-xpixmap', '.xpm' => 'image/xpm', '.x-png' => 'image/png', '.xsr' => 'video/x-amt-showrun', '.xwd' => 'image/x-xwd', '.xwd' => 'image/x-xwindowdump', '.xyz' => 'chemical/x-pdb', '.z' => 'application/x-compress', '.z' => 'application/x-compressed', '.zip' => 'application/x-compressed', '.zip' => 'application/x-zip-compressed', '.zip' => 'application/zip', '.zip' => 'multipart/x-zip', '.zoo' => 'application/octet-stream', '.zsh' => 'text/x-script.zsh)');
}

//require_once('mobile.lib.php');
