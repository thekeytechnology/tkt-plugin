<?php

add_action("plugins_loaded", function () {

    add_action("wp_enqueue_scripts", function () {
        wp_enqueue_script("tk-tracking-opt-out", plugins_url() . "/tkt-plugin/tracking-opt-out/trackingOptOut.js");
    }, 12);
    add_action("wp_enqueue_styles", function() {
        wp_enqueue_style("tk-tracking-opt-out-styles", plugins_url() . "/tkt-plugin/tracking-opt-out/trackingOptOut.css");
    }, 12);

    //Google Analytics
    add_filter("monsterinsights_track_user", function ($track_user) {
        if (
            (isset($_COOKIE["tk-tracking-opt-out"])) &&
            (false !== strpos($_COOKIE["tk-tracking-opt-out"], "ga"))
        ) {
            return false;
        }
        return $track_user;
    });

    //Facebook Pixel
    if (
        (class_exists("AEPC_Pixel_Scripts")) &&
        (isset($_COOKIE["tk-tracking-opt-out"])) &&
        (false !== strpos($_COOKIE["tk-tracking-opt-out"], "fb"))
    ) {
        add_action("wp_footer", function () {
            remove_action('wp_footer', array("AEPC_Pixel_Scripts", 'track_standard_events'), 5);
            remove_action('wp_footer', array("AEPC_Pixel_Scripts", 'track_advanced_events'), 5);
            remove_action('wp_footer', array("AEPC_Pixel_Scripts", 'track_custom_fields'), 5);
            remove_action('wp_footer', array("AEPC_Pixel_Scripts", 'track_conversions_events'), 5);
            remove_action('wp_footer', array("AEPC_Pixel_Scripts", 'enqueue_scripts'));
            remove_action('wp_footer', array("AEPC_Pixel_Scripts", 'pixel_init'), 1);
        }, 0);

        add_action("wp_head", function () {
            remove_action('wp_head', array("AEPC_Pixel_Scripts", 'pixel_init'), 99);
        }, 0);

        add_action("amp_post_template_footer", function () {
            remove_action('amp_post_template_footer', array("AEPC_Pixel_Scripts", 'track_on_amp_pages'));
        }, 0);
    }

    //Google Tag Manager
    if (
        (function_exists("gtm4wp_enqueue_scripts")) &&
        (isset($_COOKIE["tk-tracking-opt-out"])) &&
        (false !== strpos($_COOKIE["tk-tracking-opt-out"], "gtm"))
    ) {
        add_action("wp_enqueue_scripts", function () {
            remove_action("wp_enqueue_scripts", "gtm4wp_enqueue_scripts");
        }, 0);
        add_action("wp_head", function () {
            remove_action("wp_head", "gtm4wp_wp_header_begin");
            remove_action("wp_head", "gtm4wp_wp_header_top");
        }, 0);

        add_action("wp_footer", function () {
            remove_action("wp_footer", "gtm4wp_wp_footer");
        }, 0);
        add_action("wp_loaded", function () {
            remove_action("wp_loaded", "gtm4wp_wp_loaded");
        }, 0);
        add_action("body_class", function () {
            remove_action("body_class", "gtm4wp_body_class");
        }, 0);
        add_action("body_class", function () {
            remove_action("body_open", "gtm4wp_wp_body_open");
        }, 0);
    }


    add_shortcode("tkTrackingOptOutLink", function ($atts, $content = "") {
        if (!$content) {
            return "";
        }

        $type = strtolower(s("type", $atts, ""));

        switch ($type) {
            case "analytics":
            case "google":
            case "googleanalytics":
            case "ga":
                return '<a class="tk-ga-tracking-opt-out-link tk-opt-out-link">' . $content . '</a>';
                break;
            case "facebook":
            case "fb":
                return '<a class="tk-fb-tracking-opt-out-link tk-opt-out-link">' . $content . '</a>';
                break;
            case "gtm":
            case "tagmanager":
            case "googletagmanager":
                return '<a class="tk-gtm-tracking-opt-out-link tk-opt-out-link">' . $content . '</a>';
                break;
            default:
                return "";
        }
    });

}, 9999);