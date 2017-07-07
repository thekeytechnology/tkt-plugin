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
                if ($match["tag_name"] != "h2") {
                    continue;
                }
                $find[] = $match['full_tag'];
                $id = sanitize_title($match['tag_contents']);
                $id_attr = sprintf(' id="%s"', $id);
                $replace[] = sprintf('<%1$s%2$s%3$s>%4$s</%1$s>', $match['tag_name'], $match['tag_extra'], $id_attr, $match['tag_contents']);
                $tkHeadings[] = array($match['tag_contents'], $id);
            }
            $content = str_replace($find, $replace, $content);
        }

        return $content;
    }

    function tkContentSubnav()
    {
        global $tkHeadings;

        $output = "<h2 class='tk-sidebar-h2'>Inhalt</h2>";
        $output .= "<hr class='tk-hr-2'>";
        $output .= "<ol class='tk-content-subnavigation'>";

        foreach ($tkHeadings as $heading) {
            $title = $heading[0];
            $href = $heading[1];
            $output .= "<li><a href='#$href' class='scroll'>$title</a></li>";
        }
        $output .= "</ol>";

        return $output;
    }

    add_shortcode("tkContentSubnav", "tkContentSubnav");
}