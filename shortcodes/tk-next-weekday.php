
<?php

if (!function_exists("tk-next-week-day")) {
    function tkNextWeekday($atts) {


        $atts = shortcode_atts(
            array(
                'day' => 'Sunday',
            ), $atts, 'tk-next-weekday' );


        return date("d-m-Y", strtotime("next " . $atts['day']));
    }

    add_shortcode("tk-next-weekday", "tkNextWeekday");
}