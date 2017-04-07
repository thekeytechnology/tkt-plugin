<?php

if (!defined("TK_TEMPLATE_CACHE")) {
    define("TK_TEMPLATE_CACHE", false);
}

if (!defined("TK_RECAPTCHA")) {
    define("TK_RECAPTCHA", false);
}

function get_stylesheet_directory()
{

}

function add_shortcode()
{
}

include_once(__DIR__ . "/../vendor/autoload.php");
include_once("tk-twig.php");
include_once(__DIR__ . "/../utils/tk-utils.php");
include_once(__DIR__ . "/../utils/tk-wp-utils.php");