<?php
if (!function_exists("tkNextWeekday")) {
    function tkNextWeekday($atts)
    {
        $atts = shortcode_atts(
            array(
                'day' => 'Sunday',
                'format' => "d.m.Y."
            ), $atts, 'tk-next-weekday');

        tkStrToTime(array(
            "key" => "next " . $atts["day"],
            "format" => $atts["format"]
        ));
    }

    function tkStrToTime($atts)
    {
        $atts = shortcode_atts(
            array(
                'key' => 'now',
                'format' => "d.m.Y"
            ), $atts, 'tk-strtotime');
        return date($atts["format"], strtotime($atts["key"]));
    }

    add_shortcode("tk-next-weekday", "tkNextWeekday");
}