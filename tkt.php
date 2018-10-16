<?php
/*
Plugin Name: TKT Less / Gulp / Twig / WP Utilities
Plugin URI:  https://www.thekey.technology
Version:     46.1
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


require 'plugin-update-checker/plugin-update-checker.php';
$updateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://bitbucket.org/thekeytechnologies/tkt-plugin',
    __FILE__,
    'tkt-plugin'
);
$updateChecker->setBranch('master');

require_once(plugin_dir_path(__FILE__)."vendor/autoload.php");

require_once("betemplate/tk-betemplate.php");

require_once("content-subnav/tk-content-subnav.php");

require_once("cache-google-fonts/tk-cache-google-fonts.php");

require_once("options/tk-options-page.php");

require_once("conversion-tracking/tk-conversion-tracking.php");

require_once("prettyphoto/tk-prettyphoto.php");

require_once("shortcodes/tk-breadcrumbs.php");
require_once("shortcodes/tk-shortcodes.php");
require_once("shortcodes/tk-variable-parameter.php");
require_once("shortcodes/tk-read-time.php");
require_once("shortcodes/tk-next-weekday.php");

require_once("slick/tk-slick.php");

require_once("tracking-opt-out/tk-tracking-opt-out.php");

require_once("link-manipulation/link-manipulation.php");

require_once("twig/tk-twig.php");

require_once("url-param-tracker/url-param-tracker.php");
require_once("parameter-based-replacement/parameter-based-replacement.php");

require_once("utils/tk-wp-utils.php");
require_once("utils/tk-utils.php");
require_once("utils/tk-string-utils.php");
require_once("utils/tk-remove-slug.php");
require_once("utils/tk-remove-empty-elements.php");
require_once("utils/tk-mail.php");
require_once("utils/tk-image-title.php");
require_once("utils/tk-image-fixes.php");

add_action("wp_enqueue_scripts", function () {
    wp_enqueue_script("tk-prevent-enter-submit", plugins_url() . "/tkt-plugin/utils/js/preventEnterSubmit.js", array("jquery"));
}, 11);


add_action("body_class", function ($classes) {
    if (get_post_meta(get_queried_object_id(), "tk-bodyclass", true)) {

        $classesToBeAdded = explode(" ", get_post_meta(get_queried_object_id(), "tk-bodyclass", true));

        return array_merge($classes, $classesToBeAdded);
    } else {
        return $classes;
    }
});


add_filter("wp_theme_editor_filetypes", function ($types) {
    $types[] = "twig";

    return $types;
});
