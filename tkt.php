<?php
/*
Plugin Name: TKT Less / Gulp / Twig
Plugin URI:  https://www.thekey.technology
Version:     7.0
Author:      the key technology
Author URI:  https://www.thekey.technology
License:     proprietary
Text Domain: tkt
*/

if (!defined("TK_TEMPLATE_CACHE")) {
    define("TK_TEMPLATE_CACHE", false);
}

if (!defined("TK_RECAPTCHA")) {
    define("TK_RECAPTCHA", false);
}

if (!defined("TK_MAIL_HTML")){
    define("TK_MAIL_HTML", false);
}

require_once "vendor/autoload.php";

require_once("utils/tk-wp-utils.php");
require_once("utils/tk-utils.php");
require_once("utils/tk-remove-empty-elements.php");
require_once("utils/tk-mail.php");
require_once("utils/tk-image-title.php");
require_once("utils/tk-conversion-tracking.php");

require_once("shortcodes/tk-shortcodes.php");

require_once("twig/tk-twig.php");

require_once("content-subnav/tk-content-subnav.php");

require_once("betemplate/tk-betemplate.php");
