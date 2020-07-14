<?php

function tkGoogleMapsEnqueueFiles() {
    /* Loads all Maps on the Page when User click on a Map */
    wp_enqueue_script("tk-google-maps-js", plugin_dir_url(__FILE__) . '/tk-google-maps.js', "jquery", false, true);

    /* Set The API Key and the connected Borlabs Cookie

        Example:
        add_filter('tk-google-maps-api-key', function () {
            return "your key here";
        });
        add_filter('tk-google-maps-borlabs-cookie', function () {
            return array(
                "group" => "external-media",
                "cookie" => "googlemaps"
            );
        });
    */
    wp_localize_script("tk-google-maps-js", "tkGoogleMapsOptions", array(
        "mapKey" => apply_filters("tk-google-maps-api-key", ""),
        "borlabsCookie" => json_encode(apply_filters("tk-google-maps-borlabs-cookie", false)),
        "alwaysLoad" => apply_filters("tk-google-maps-always-load", false)
    ));

}
add_action('wp_enqueue_scripts', 'tkGoogleMapsEnqueueFiles', 11);
