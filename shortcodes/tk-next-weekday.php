
<?php
if (!function_exists("tkNextWeekday")) {

    echo "<h1 style='display: none' class='tk-test'></h1>";
    function tkNextWeekday($atts) {


        $atts = shortcode_atts(
            array(
                'day' => 'Sunday',
            ), $atts, 'tk-next-weekday' );


        return date("d.m.Y", strtotime("next sunday"));
    }

    add_shortcode("tk-next-weekday", "tkNextWeekday");

}