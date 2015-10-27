<?php if (session_id() == '') {
    session_start();
}

define('SESSION_LOCALE_KEY', 'locale');
define('DEFAULT_LOCALE', $_SESSION['LANGUAGES'][0]);
define('LOCALE_REQUEST_PARAM', 'lang');
define('WEBSITE_DOMAIN', 'messages');

if (array_key_exists(LOCALE_REQUEST_PARAM, $_REQUEST)) {
    $current_locale = $_REQUEST[LOCALE_REQUEST_PARAM];
    $_SESSION[SESSION_LOCALE_KEY] = $current_locale;
} elseif (array_key_exists(SESSION_LOCALE_KEY, $_SESSION)) {
    $current_locale = $_SESSION[SESSION_LOCALE_KEY];
} else {
     $_SESSION[SESSION_LOCALE_KEY] = DEFAULT_LOCALE;
}

putenv("LC_ALL=$current_locale");
setlocale(LC_ALL, $current_locale.'.UTF-8');
bindtextdomain(WEBSITE_DOMAIN, dirname(__FILE__) . '/languages');
bind_textdomain_codeset(WEBSITE_DOMAIN, 'UTF-8');
textdomain(WEBSITE_DOMAIN);
