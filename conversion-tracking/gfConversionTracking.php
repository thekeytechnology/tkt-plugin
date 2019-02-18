<?php
/**
 * @param $confirmation
 * @param $form
 * @param $entry
 * @param $is_ajax
 */
function tk_gform_tracking($confirmation, $form, $entry, $is_ajax) {
    global $tkGfConversionParams;

    $script = '<script type="text/javascript">
        var tkGfConversionTrackingParameters = ' . json_encode($tkGfConversionParams) . ';
        var tkGfConversionTrackingInputs = ' . json_encode($_POST)  . ';
    </script>';

    $script .= '<script type="text/javascript" src="' . plugins_url()."/tkt-plugin/conversion-tracking/gfConversionTracking.js" . '"></script>';

    return $confirmation .  $script;
}



add_filter( 'gform_confirmation', 'tk_gform_tracking', 10, 4 );