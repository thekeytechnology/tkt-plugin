<?php

function tkInstallCF7ConversionTracking()
{
    add_action("wp_enqueue_scripts", function(){
        wp_enqueue_script("tk-cf7-conversion-tracking", plugins_url()."/tkt-plugin/conversion-tracking/cf7ConversionTracking.js");
    }, 11);
}

function tkInstallCallConversionTracking($category = "Conversion", $action="Anruf", $label="", $value=0)
{
    $params = array(
        "category" => $category,
        "action" => $action,
        "label" => $label,
        "value" => $value);

    add_action("wp_enqueue_scripts", function() use ($params){
        wp_enqueue_script("tk-call-conversion-tracking", plugins_url()."/tkt-plugin/conversion-tracking/callConversionTracking.js", array("jquery"));
        wp_localize_script("tk-call-conversion-tracking", "tkCallConversionTrackingParameters", $params);
    }, 11);
}

function tkInstallMailConversionTracking($category = "Conversion", $action="E-Mail", $label="", $value=0)
{
    $params = array(
        "category" => $category,
        "action" => $action,
        "label" => $label,
        "value" => $value);

    add_action("wp_enqueue_scripts", function() use ($params){
        wp_enqueue_script("tk-mail-conversion-tracking", plugins_url()."/tkt-plugin/conversion-tracking/mailConversionTracking.js", array("jquery"));
        wp_localize_script("tk-mail-conversion-tracking", "tkMailConversionTrackingParameters", $params);
    }, 11);
}

function tkInstallMailchimpConversionTracking()
{
    add_action("wp_enqueue_scripts", function(){
        wp_enqueue_script("tk-mailchimp-conversion-tracking", plugins_url()."/tkt-plugin/conversion-tracking/mailchimpConversionTracking.js");
    }, 11);
}

function tkInstallConversionTracking($cf7 = true, $mail = true, $call = true, $mailchimp = true) {
    if ($cf7) {
        tkInstallCF7ConversionTracking();
    }

    if ($mail) {
        tkInstallCallConversionTracking();
    }

    if ($call) {
        tkInstallMailConversionTracking();
    }

    if ($mailchimp) {
        tkInstallMailchimpConversionTracking();
    }
}