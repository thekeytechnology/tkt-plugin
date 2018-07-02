<?php

function tkInstallUrlParamTracker ($options = array())
{
    add_action("wp_enqueue_scripts", function () use ($options) {
        wp_enqueue_script("tk-upt", plugins_url() . "/tkt-plugin/url-param-tracker/tk-upt.js", array("jquery"));
        wp_localize_script("tk-upt", "tkUPTOptions", $options);
    }, 12);

    add_shortcode("tk-upt-cookie-cf7-input", function () {

        $output = '
                <input name="tk-upt-traffic-source" class="tk-upt-traffic-source tk-upt-input" value="" type="hidden">
                <input name="tk-upt-gclid" class="tk-upt-gclid tk-upt-input" value="" type="hidden">
                <input name="tk-upt-utm_source" class="tk-upt-utm_source tk-upt-input" value="" type="hidden">
                <input name="tk-upt-utm_medium" class="tk-upt-utm_medium tk-upt-input" value="" type="hidden">
                <input name="tk-upt-utm_campaign" class="tk-upt-utm_campaign tk-upt-input" value="" type="hidden">
                <input name="tk-upt-utm_term" class="tk-upt-utm_term tk-upt-input" value="" type="hidden">
                <input name="tk-upt-utm_content" class="tk-upt-utm_content tk-upt-input" value="" type="hidden">
                <input name="tk-upt-campaign" class="tk-upt-campaign tk-upt-input" value="" type="hidden">
                <input name="tk-upt-referrer" class="tk-upt-referrer tk-upt-input" value="" type="hidden">';

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