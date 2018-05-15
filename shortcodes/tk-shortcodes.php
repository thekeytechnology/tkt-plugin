<?php

function tkAttribute($atts)
{
    $args = shortcode_atts(array(
        "field" => "",
        "single" => true
    ), $atts, "tk-attribute");

    return get_post_meta(get_queried_object_id(), $args["field"], $args["single"]);
}
add_shortcode("tk-attribute", function ($atts) {

});


add_shortcode("tk-year", function () {
    return date("Y");
});