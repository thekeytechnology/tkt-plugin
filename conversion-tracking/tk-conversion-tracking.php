<?php

/*
 * Put <input type="hidden" name="tk-conversion-action" value="name_of_conversion"/> into forms where conversion tracking is supposed to occur.
 * */

function tkInstallCF7ConversionTracking()
{
    wp_enqueue_script("tk-cf7-conversion-tracking", plugins_url()."/tkt-plugin/conversion-tracking/cf7ConversionTracking.js");
}


function tkInstallCallConversionTracking()
{
    wp_enqueue_script("tk-call-conversion-tracking", plugins_url()."/tkt-plugin/conversion-tracking/callConversionTracking.js", array("jquery"));
}