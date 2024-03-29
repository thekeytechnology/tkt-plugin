<?php

function tkEnableSubnavigation()
{
    function tkSubnavFiles()
    {
        wp_enqueue_script('jquery-waypoints', plugins_url() . '/tkt-plugin/content-subnav/js/jquery.waypoints.min.js', array("jquery"));
        wp_enqueue_script('tk-child-script', plugins_url() . '/tkt-plugin/content-subnav/js/tk-content-subnav.js', array("jquery", "jquery-waypoints"));
    }

    add_action('wp_enqueue_scripts', 'tkSubnavFiles', 11);


    //add_filter('the_content', 'add_ids_to_header_tags');
    function add_ids_to_header_tags($content)
    {

        global $tkHeadings;
        $tkHeadings = array();

        $pattern = '#(?P<full_tag><(?P<tag_name>h\d)(?P<tag_extra>[^>]*)>(?P<tag_contents>[^<]*)</h\d>)#i';
        if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
            $find = array();
            $replace = array();
            foreach ($matches as $match) {
                if (strlen($match['tag_extra']) && false !== stripos($match['tag_extra'], 'id=')) {
                    continue;
                }
                if ($match["tag_name"] != "h2" and $match["tag_name"] != "h3") {
                    continue;
                }
                $find[] = $match['full_tag'];
                $id = sanitize_title($match['tag_contents']);
                $id_attr = sprintf(' id="%s"', $id);
                $headingFormat = apply_filters("tk-filter-subnav-heading-format", '<%1$s%2$s%3$s>%4$s</%1$s>');
                $replace[] = sprintf($headingFormat, $match['tag_name'], $match['tag_extra'], $id_attr, $match['tag_contents']);

                $tkHeadings[] =
                    array(
                        "type" => $match["tag_name"],
                        "id" => $id,
                        "title" => $match['tag_contents']
                    );
            }
            $content = str_replace($find, $replace, $content);
        }

        return $content;
    }

    function tkContentSubnav($atts = null)
    {
        global $tkHeadings;

        if (!$tkHeadings) {
            $queriedObject = get_queried_object();
            $rawContent = "";
            if ($queriedObject instanceof WP_Post) {
                $rawContent = $queriedObject->post_content;
            } else if ($queriedObject instanceof WP_Term) {
                $rawContent = $queriedObject->description;
            }

            apply_filters("the_content", $rawContent);
        }

        if (!$tkHeadings) {
            $tkHeadings = array();
        }

        $tkHeadings = apply_filters("tk-filter-subnav-headings", $tkHeadings);

        $h3Enabled = $atts["enableh3"];

        $output = "";
        if (!$atts["disabletitle"]) {
            $title = $atts["title"];
            if (!$title) {
                $title = "Inhalt";
            }
            $output .= "<h2 class='tk-sidebar-h2'>$title</h2>";
        }

        $disableHr = $atts["disablehr"];
        if (!$disableHr) {
            $output .= "<hr class='tk-hr-2'>";
        }

        $output .= "<ol class='tk-content-subnavigation'>";

        $lastType = false;
        foreach ($tkHeadings as $heading) {
            $type = $heading["type"];

            if ($type == "h3" and !$h3Enabled) {
                continue;
            }

            $title = $heading["title"];
            $id = $heading["id"];
            if ($type == "h3" and $lastType == "h2") {
                $output .= "<ol class='tk-content-subsubnavigation'>";
            } else if ($type == "h2" and $lastType == "h3") {
                $output .= "</li></ol>";
            } else {
                $output .= "</li>";
            }

            $output .= "<li><a href='#$id' class='scroll'>$title</a>";
            $lastType = $type;
        }
        if ($lastType == "h3") {
            $output .= "</ol>";
        }
        $output .= "</li>";

        $output .= "</ol>";

        return $output;
    }

    add_shortcode("tkContentSubnav", "tkContentSubnav");
}

function tkAddSidebarToPostType($postType, $onArchives = false)
{
    add_filter('get_post_metadata', function ($metadata, $object_id, $meta_key, $single) use ($postType, $onArchives) {
        $postType = get_post_type($object_id);

        if ($postType == $postType) {
            $show = $onArchives ? is_archive() : !is_archive();
            if (!$show) {
                return $metadata;
            }

            if (isset($meta_key) && 'mfn-post-sidebar' == $meta_key) {
                return '0';
            }
            if (isset($meta_key) && 'mfn-post-layout' == $meta_key) {
                return 'right-sidebar';
            }
        }
        return $metadata;
    }, 100, 4);


    add_filter('body_class', function ($classes) use ($postType, $onArchives) {
        $postType = get_post_type();

        $show = $onArchives ? is_archive() : !is_archive();

        if ($postType == $postType and $show) {
            return array_merge($classes, array('page-template-template-tk-content'));
        }
        return $classes;
    });
}
