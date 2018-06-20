<?php

function tkInstallUrlParamTracker ()
{
    add_action("wp_enqueue_scripts", function () {
        wp_enqueue_script("tk-upt", plugins_url() . "/tkt-plugin/url-param-tracker/tk-upt.js", array("jquery"));
    }, 12);

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
        $output .= '<input type="hidden" name="tk-upt-traffic-source" id="tk-upt-traffic-source" value="' . $trafficSource . '"/>';

        $output .= '<input type="hidden" name="tk-upt-gclid" id="tk-upt-gclid" class="tk-upt-input" value="' . wp_kses($gclid, array(), array()) . '"/>';

        $output .= '<input type="hidden" name="tk-upt-utm_source" id="tk-upt-utm_source" class="tk-upt-input" value="' . $utmSource . '"/>';

        $value = s("utm_medium", $cookie, "");
        $output .= '<input type="hidden" name="tk-upt-utm_medium" id="tk-upt-utm_medium" class="tk-upt-input" value="' . wp_kses($value, array(), array()) . '"/>';

        $value = s("utm_campaign", $cookie, "");
        $output .= '<input type="hidden" name="tk-upt-utm_campaign" id="tk-upt-utm_campaign" class="tk-upt-input" value="' . wp_kses($value, array(), array()) . '"/>';

        $value = s("utm_term", $cookie, "");
        $output .= '<input type="hidden" name="tk-upt-utm_term" id="tk-upt-utm_term" class="tk-upt-input" value="' . wp_kses($value, array(), array()) . '"/>';

        $value = s("utm_content", $cookie, "");
        $output .= '<input type="hidden" name="tk-upt-utm_content" id="tk-upt-utm_content" class="tk-upt-input" value="' . wp_kses($value, array(), array()) . '"/>';

        $value = s("campaign", $cookie, "");
        $output .= '<input type="hidden" name="tk-upt-campaign" id="tk-upt-campaign" class="tk-upt-input" value="' . wp_kses($value, array(), array()) . '"/>';

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

function tkGetUPTCookie ()
{
    $cookie = s("tk-upt", $_COOKIE, "");
    if ($cookie) {
        $cookie = stripslashes($cookie);
        return json_decode($cookie, true);
    }
    return array();
}