<?php
/**
 * Created by IntelliJ IDEA.
 * User: dan
 * Date: 22.08.18
 * Time: 15:26
 */

$pbr_list = array();

function tkInstallParameterBasedReplacement ($search , $replace, $parameter  = 'utm_source', $value = 'google-ads') {

    global $pbr_list;

    require_once("parameter-based-replacement-include-js.php");

    if (isset($_GET[$parameter]) ) {
        if ($_GET[$parameter] == $value) {
            $pbr_list[] = [$search, $replace];
        }
    }
}