<?php

//make WP also add the title to images (doesn't affect tags in visual editor)

//for img tags in the visual editor, use the Restore Image Title plugin

function tkInstallImageTitle(){
    add_filter("wp_get_attachment_image_attributes", function ($atts, $attachment) {
        $atts["title"] = get_the_title($attachment);
        return $atts;
    }, 10, 2);
}
