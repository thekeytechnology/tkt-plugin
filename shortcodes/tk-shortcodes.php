<?php

function tkAttribute($atts)
{
    return get_post_meta(get_queried_object_id(), $atts["field"], true);
}

add_shortcode("tk-attribute", "tkAttribute");