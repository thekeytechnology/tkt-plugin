<?php

function tkAttribute($atts)
{
    $args = shortcode_atts(array(
        "field" => "",
        "single" => true
    ), $atts, "tk-attribute");

    return get_post_meta(get_queried_object_id(), $args["field"], $args["single"]);
}
add_shortcode("tk-attribute", "tkAttribute");

add_shortcode("tk-option", function($atts) {
    $atts = shortcode_atts(array(
        "option" => "",
        "default" => "",
        "do_shortcode" => false
    ), $atts, "tk-option");
    return $atts["do_shortcode"] ? do_shortcode(get_option($atts["option"], $atts["default"])) : get_option($atts["option"], $atts["default"]);
});

add_shortcode("tk-year", function () {
    return date("Y");
});