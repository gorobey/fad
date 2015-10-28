<?php
usleep(500); //1/2 sec
//DDOS MITIGATION:
if($_SESSION['last_session_request'] > (time() - 3)){
	if(empty($_SESSION['last_request_count'])){
		$_SESSION['last_request_count'] = 1;
	}elseif($_SESSION['last_request_count'] < 5){
		$_SESSION['last_request_count'] = $_SESSION['last_request_count'] + 1;
	}elseif($_SESSION['last_request_count'] >= 5){
		sleep(2);
	}elseif($_SESSION['last_request_count'] >= 25){
		die();
	}
}else{
	$_SESSION['last_request_count'] = 1;
}
$_SESSION['last_session_request'] = time();
header('Content-Type: text/html; charset=utf-8');
//SECURE INCLUSION MODULES
$_CONFIG['tabasco'] = crypt($_CONFIG['pepper'], $_CONFIG['salt']);//da lavorare
//DEV CONFIG
if($_SESSION['DEV']===TRUE){
	ini_set("display_errors", "1");
	error_reporting(E_ALL);
}
$_CONFIG['check_table'] = array(//da sistemare (non ricordo a che serve e se è ancora in uso)
	"name" => "check_global",
	"surname" => "check_global",
	"password" => "check_global",
	"mail" => "check_global"
);

//database basic tables name
//basic infos
$_CONFIG['info'] = "infos";

//users management
$_CONFIG['users'] = "users";
$_CONFIG['attr'] = "user_attr";
$_CONFIG['groups'] = "user_groups";
$_CONFIG['roles'] = "user_roles";

//login sistem
$_CONFIG['login'] = "login_attempts";
$_CONFIG['session'] = "sessions";
$_CONFIG['analytics'] = "analytics";

//content management
$_CONFIG['taxonomy'] = "taxonomy";
$_CONFIG['item'] = "item";
$_CONFIG['locale'] = "locale";

//database tables renamed
$_CONFIG['t_info'] = $_CONFIG['table_prefix'].$_CONFIG['info'];
$_CONFIG['t_users'] = $_CONFIG['table_prefix'].$_CONFIG['users'];
$_CONFIG['t_attr'] = $_CONFIG['table_prefix'].$_CONFIG['attr'];
$_CONFIG['t_groups'] = $_CONFIG['table_prefix'].$_CONFIG['groups'];
$_CONFIG['t_roles'] = $_CONFIG['table_prefix'].$_CONFIG['roles'];
$_CONFIG['t_login'] = $_CONFIG['table_prefix'].$_CONFIG['login'];
$_CONFIG['t_session'] = $_CONFIG['table_prefix'].$_CONFIG['session'];
$_CONFIG['t_analytics'] = $_CONFIG['table_prefix'].$_CONFIG['analytics'];
$_CONFIG['t_taxonomy'] = $_CONFIG['table_prefix'].$_CONFIG['taxonomy'];
$_CONFIG['t_item'] = $_CONFIG['table_prefix'].$_CONFIG['item'];
$_CONFIG['t_locale'] = $_CONFIG['table_prefix'].$_CONFIG['locale'];

//users status
define('AUTH_LOGGED', 99);
define('AUTH_NOT_LOGGED', 100);
define('AUTH_USE_COOKIE', 101);
define('AUTH_USE_LINK', 103);//activation account // reset password
define('AUTH_INVALID_PARAMS', 104);
define('AUTH_LOGEDD_IN', 105);
define('AUTH_FAILED', 106);
define('AUTH_BRUTE_FORCE', 999);
define('REG_ERRORS', 107);
define('REG_SUCCESS', 108);
define('REG_FAILED', 109);

/**
    ISO 639-1 Language Codes
    Useful in Locale analysis
    References :
    1. http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
    2. http://blog.xoundboy.com/?p=235
*/
$_CONFIG['language_codes'] = array('en' => 'English', 'aa' => 'Afar', 'ab' => 'Abkhazian', 'af' => 'Afrikaans', 'am' => 'Amharic', 'ar' => 'Arabic', 'as' => 'Assamese', 'ay' => 'Aymara', 'az' => 'Azerbaijani', 'ba' => 'Bashkir', 'be' => 'Byelorussian', 'bg' => 'Bulgarian', 'bh' => 'Bihari', 'bi' => 'Bislama', 'bn' => 'Bengali/Bangla', 'bo' => 'Tibetan', 'br' => 'Breton', 'ca' => 'Catalan', 'co' => 'Corsican', 'cs' => 'Czech', 'cy' => 'Welsh', 'da' => 'Danish', 'de' => 'German', 'dz' => 'Bhutani', 'el' => 'Greek', 'eo' => 'Esperanto', 'es' => 'Spanish', 'et' => 'Estonian', 'eu' => 'Basque', 'fa' => 'Persian', 'fi' => 'Finnish', 'fj' => 'Fiji', 'fo' => 'Faeroese', 'fr' => 'French', 'fy' => 'Frisian', 'ga' => 'Irish', 'gd' => 'Scots/Gaelic', 'gl' => 'Galician', 'gn' => 'Guarani', 'gu' => 'Gujarati', 'ha' => 'Hausa', 'hi' => 'Hindi', 'hr' => 'Croatian', 'hu' => 'Hungarian', 'hy' => 'Armenian', 'ia' => 'Interlingua', 'ie' => 'Interlingue', 'ik' => 'Inupiak', 'in' => 'Indonesian', 'is' => 'Icelandic', 'it' => 'Italian', 'iw' => 'Hebrew', 'ja' => 'Japanese', 'ji' => 'Yiddish', 'jw' => 'Javanese', 'ka' => 'Georgian', 'kk' => 'Kazakh', 'kl' => 'Greenlandic', 'km' => 'Cambodian', 'kn' => 'Kannada', 'ko' => 'Korean', 'ks' => 'Kashmiri', 'ku' => 'Kurdish', 'ky' => 'Kirghiz', 'la' => 'Latin', 'ln' => 'Lingala', 'lo' => 'Laothian', 'lt' => 'Lithuanian', 'lv' => 'Latvian/Lettish', 'mg' => 'Malagasy', 'mi' => 'Maori', 'mk' => 'Macedonian', 'ml' => 'Malayalam', 'mn' => 'Mongolian', 'mo' => 'Moldavian', 'mr' => 'Marathi', 'ms' => 'Malay', 'mt' => 'Maltese', 'my' => 'Burmese', 'na' => 'Nauru', 'ne' => 'Nepali', 'nl' => 'Dutch', 'no' => 'Norwegian', 'oc' => 'Occitan', 'om' => '(Afan)/Oromoor/Oriya', 'pa' => 'Punjabi', 'pl' => 'Polish', 'ps' => 'Pashto/Pushto', 'pt' => 'Portuguese', 'qu' => 'Quechua', 'rm' => 'Rhaeto-Romance', 'rn' => 'Kirundi', 'ro' => 'Romanian', 'ru' => 'Russian', 'rw' => 'Kinyarwanda', 'sa' => 'Sanskrit', 'sd' => 'Sindhi', 'sg' => 'Sangro', 'sh' => 'Serbo-Croatian', 'si' => 'Singhalese', 'sk' => 'Slovak', 'sl' => 'Slovenian', 'sm' => 'Samoan', 'sn' => 'Shona', 'so' => 'Somali', 'sq' => 'Albanian', 'sr' => 'Serbian', 'ss' => 'Siswati', 'st' => 'Sesotho', 'su' => 'Sundanese', 'sv' => 'Swedish', 'sw' => 'Swahili', 'ta' => 'Tamil', 'te' => 'Tegulu', 'tg' => 'Tajik', 'th' => 'Thai', 'ti' => 'Tigrinya', 'tk' => 'Turkmen', 'tl' => 'Tagalog', 'tn' => 'Setswana', 'to' => 'Tonga', 'tr' => 'Turkish', 'ts' => 'Tsonga', 'tt' => 'Tatar', 'tw' => 'Twi', 'uk' => 'Ukrainian', 'ur' => 'Urdu', 'uz' => 'Uzbek', 'vi' => 'Vietnamese', 'vo' => 'Volapuk', 'wo' => 'Wolof', 'xh' => 'Xhosa', 'yo' => 'Yoruba', 'zh' => 'Chinese', 'zu' => 'Zulu');

$db_conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die ('database connection error :(');//cambiare in $_CONFIG['db_conn']
// Change character set to utf8
mysqli_set_charset($db_conn,"utf8");
