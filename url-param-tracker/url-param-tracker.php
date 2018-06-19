<?php

function tkInstallUrlParamTracker ()
{
    add_action("init", function () {
        $setCookie = false;
        $data = array();
        if (isset($_GET["gclid"])) {
            $data["gclid"] = $_GET["gclid"];
            $setCookie = true;
        }

        if (isset($_GET["utm_source"])) {
            $data["utm_source"] = $_GET["utm_source"];
            $data = tkAddParameterToArrayIfSet("utm_medium", $data);
            $data = tkAddParameterToArrayIfSet("utm_campaign", $data);
            $data = tkAddParameterToArrayIfSet("utm_term", $data);
            $data = tkAddParameterToArrayIfSet("utm_content", $data);
            $setCookie = true;
        }

        if ($setCookie) {
            if ( isset($_GET["campaign"]) ) {
                $data["campaign"] = base64_decode($_GET["campaign"]);
            }
            setcookie("tk-upt", json_encode($data), (time() + 86400 * 30), COOKIEPATH, COOKIE_DOMAIN);
        }
    });

    add_shortcode("tk-upt-cookie-value", function ($attr, $content = "") {
        $attr = shortcode_atts(array(
            "param" => "",
            "return_content_if_no_param" => false
        ), $attr);

        $output = s($attr["param"], tkGetUPTCookie(), "");
        $output = wp_kses($output, array(), array());
        if ($output) {
            $output = $content . $output;
        }

        if (!$output && $attr["return_content_if_no_param"]) {
            $output = $content;
        }
        return $output;
    });

    add_shortcode("tk-upt-cookie-cf7-input", function () {
        $output = "";

        $cookie = tkGetUPTCookie();
        $trafficSource = s("gclid", $cookie, "");
        $gclid = "";
        $utmSource = "";
        if ($trafficSource) {
            $gclid = $trafficSource;
            $trafficSource = __("AdWords", "tkt-plugin");
        } else {
            $trafficSource = s("utm_source", $cookie, "");
            if ($trafficSource) {
                $trafficSource = wp_kses($trafficSource, array(), array());
                $utmSource = $trafficSource;
            } else {
                $trafficSource = __("organisch", "tkt-plugin");
            }
        }
        $output .= '<input type="hidden" name="tk-upt-traffic-source" value="' . $trafficSource . '"/>';

        $output .= '<input type="hidden" name="tk-upt-gclid" value="' . wp_kses($gclid, array(), array()) . '"/>';

        $output .= '<input type="hidden" name="tk-upt-utm_source" value="' . $utmSource . '"/>';

        $value = s("utm_medium", $cookie, "");
        $output .= '<input type="hidden" name="tk-upt-utm_medium" value="' . wp_kses($value, array(), array()) . '"/>';

        $value = s("utm_campaign", $cookie, "");
        $output .= '<input type="hidden" name="tk-upt-utm_campaign" value="' . wp_kses($value, array(), array()) . '"/>';

        $value = s("utm_term", $cookie, "");
        $output .= '<input type="hidden" name="tk-upt-utm_term" value="' . wp_kses($value, array(), array()) . '"/>';

        $value = s("utm_content", $cookie, "");
        $output .= '<input type="hidden" name="tk-upt-utm_content" value="' . wp_kses($value, array(), array()) . '"/>';

        $value = s("campaign", $cookie, "");
        $output .= '<input type="hidden" name="tk-upt-campaign" value="' . wp_kses($value, array(), array()) . '"/>';

        return $output;
    });

    if (false === has_filter("wpcf7_form_elements", "do_shortcode")) {
        add_filter('wpcf7_form_elements', 'do_shortcode');
    }
    add_filter("wpcf7_mail_components", function ($components, $wpcf7_current_form, $instance) {
        $components["subject"] = do_shortcode($components["subject"]);
        $components["body"] = do_shortcode($components["body"]);
        return $components;
    }, 10, 3);
}

function tkAddParameterToArrayIfSet ($param, $array)
{
    if ( isset($_GET[$param]) ) {
        $array[$param] = $_GET[$param];
    }
    return $array;
}

function tkGetUPTCookie ()
{
    $cookie = s("tk-upt", $_COOKIE, "");
    if ($cookie) {
        $cookie = stripslashes($cookie);
        return json_decode($cookie, true);
    }
    return array();
}