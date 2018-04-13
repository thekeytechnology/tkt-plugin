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

function tkInstallSignUpConversionTracking($category = "Conversion", $action="SignUp", $label="", $value=0) {

    $params = array(
        "category" => $category,
        "action" => $action,
        "label" => $label,
        "value" => $value
    );

    add_action( 'user_register', function() use ($params) {
        wp_enqueue_script("tk-signup-conversion-tracking", plugins_url()."/tkt-plugin/conversion-tracking/signUpConversionTracking.js", array("jquery"));
        wp_localize_script("tk-signup-conversion-tracking", "tkSignUpConversionTrackingParameters", $params);
    }, 10, 1 );
}

function tkInstallConversionTracking($cf7 = true, $mail = true, $call = true, $mailchimp = true, $signups = true) {
    if ($cf7) {
        if (function_exists("tkInstallCF7ConversionTracking")) {
            tkInstallCF7ConversionTracking();
        }
    }

    if ($mail) {
        if (function_exists("tkInstallCallConversionTracking")) {
            tkInstallCallConversionTracking();
        }
    }

    if ($call) {
        if (function_exists("tkInstallMailConversionTracking")) {
            tkInstallMailConversionTracking();
        }
    }

    if ($mailchimp) {
        if (function_exists("tkInstallMailchimpConversionTracking")) {
            tkInstallMailchimpConversionTracking();
        }
    }

    if ($signups) {
        if (function_exists("tkInstallSignUpConversionTracking")) {
            tkInstallSignUpConversionTracking();
        }
    }
}