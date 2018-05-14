<?php

global $tkOptOutCookieNames;

global $tkOptOutNotices;
$tkOptOutNotices["googleAnalytics"] = false;
$tkOptOutNotices["facebook"] = false;
$tkOptOutNotices["gtm"] = false;

add_action("plugins_loaded", function () {
    global $tkOptOutCookieNames;

    $tkOptOutCookieNames["GA"] = (function_exists("monsterinsights_get_ua") ? "tk-ga-disable-" . monsterinsights_get_ua() : "");
    $tkOptOutCookieNames["FB"] = (class_exists("AEPC_Pixel_Scripts")) ? $tkOptOutCookieNames["FB"] = "tk-fb-disable-" . get_option("aepc_pixel_id") : "";
    $tkOptOutCookieNames["GTM"] = (function_exists("gtm4wp_enqueue_scripts")) ? "tk-gtm-disable" : "";

    add_action("wp_enqueue_scripts", function () {
        global $tkOptOutCookieNames;
        $params = array(
            "GA" => $tkOptOutCookieNames["GA"],
            "FB" => $tkOptOutCookieNames["FB"],
            "GTM" => $tkOptOutCookieNames["GTM"]
        );

        wp_enqueue_script("tk-tracking-opt-out", plugins_url() . "/tkt-plugin/tracking-opt-out/trackingOptOut.js");
        wp_localize_script("tk-tracking-opt-out", "tkOptOutCookieNames", $params);
    }, 12);

    add_filter("monsterinsights_track_user", function ($track_user) {

        global $tkOptOutCookieNames;
        if ((!$tkOptOutCookieNames["GA"]) || (!isset($_COOKIE[$tkOptOutCookieNames["GA"]]))) {
            return $track_user;
        }

        global $tkOptOutNotices;
        if (!$tkOptOutNotices["googleAnalytics"]) {
            echo "<!-- Google Analytics tracking disabled via user opt-out cookie -->" . PHP_EOL;
            $tkOptOutNotices["googleAnalytics"] = true;
        }

        return false;
    });

    if (($tkOptOutCookieNames["FB"]) && (isset($_COOKIE[$tkOptOutCookieNames["FB"]]))) {
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

        add_action("wp_head", function () {
            global $tkOptOutNotices;
            if (!$tkOptOutNotices["facebook"]) {
                echo "<!-- Facebook Pixel disabled via user opt-out cookie -->" . PHP_EOL;
                $tkOptOutNotices["facebook"] = true;
            }
        });
    }

    if (($tkOptOutCookieNames["GTM"]) && (isset($_COOKIE[$tkOptOutCookieNames["GTM"]]))) {
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
        global $tkOptOutCookieNames;

        if (!$content) {
            return "";
        }

        $type = strtolower(s("type", $atts, ""));

        switch ($type) {
            case "analytics":
            case "google":
            case "googleanalytics":
            case "ga":
                if (!$tkOptOutCookieNames["GA"]) {
                    return "";
                }
                return '<a class="tk-ga-tracking-opt-out-link">' . $content . '</a>';
                break;
            case "facebook":
            case "fb":
                if (!$tkOptOutCookieNames["FB"]) {
                    return "";
                }
                return '<a class="tk-fb-tracking-opt-out-link">' . $content . '</a>';
                break;
            case "gtm":
            case "tagmanager":
            case "googletagmanager":
                if (!$tkOptOutCookieNames["GTM"]) {
                    return "";
                }
                return '<a class="tk-gtm-tracking-opt-out-link">' . $content . '</a>';
                break;
            default:
                return "";
        }
    });

}, 9999);