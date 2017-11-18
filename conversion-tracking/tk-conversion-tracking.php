<?php

function tkInstallCF7ConversionTracking()
{
    add_action("wp_enqueue_scripts", function(){
        wp_enqueue_script("tk-cf7-conversion-tracking", plugins_url()."/tkt-plugin/conversion-tracking/cf7ConversionTracking.js");
    }, 11);
}


function tkInstallCallConversionTracking()
{
    add_action("wp_enqueue_scripts", function(){
        wp_enqueue_script("tk-call-conversion-tracking", plugins_url()."/tkt-plugin/conversion-tracking/callConversionTracking.js", array("jquery"));

    }, 11);
}