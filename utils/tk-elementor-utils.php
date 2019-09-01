<?php

/* Since our wpcontent filter does not use the_content, we
need a special function to retreive that content */
function tkElementorContent($item)
{
    $content = tkElementorRawContent($item);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    return $content;
}

function tkElementorRawContent($item)
{
    if (empty($item)) {
        return "";
    } else if ($item instanceof WP_Term) {
        return $item->description;
    } else if ($item instanceof WP_Post) {
        return \Elementor\Plugin::instance()->frontend->get_builder_content($item->ID);
    } else if (is_array($item)) {
        if (isset($item["description"])) {
            return $item["description"];
        } else if (isset($item["ID"])) {
            return \Elementor\Plugin::instance()->frontend->get_builder_content($item["ID"]);
        }
    }
    throw new Exception("This type is not supported! " . print_r($item, true));
}

/* When applying the 'the_content' filter, elementor adds the content of the current page
on its own for some reason, so here we just apply the default filters */
function tkElementorWpContent($item) {
    $content = tkWpRawContent($item);
    $content = wptexturize($content);
    $content = convert_smilies($content);
    $content = wpautop($content);
    $content = str_replace(']]>', ']]&gt;', $content);
    return $content;
}
