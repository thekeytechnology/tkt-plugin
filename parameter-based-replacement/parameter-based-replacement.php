<?php
/**
 * Created by IntelliJ IDEA.
 * User: dan
 * Date: 22.08.18
 * Time: 15:26
 */

$pbr_list = array();

function tkInstallParameterBasedReplacement ($search , $replace, $parameter  = 'gclid', $value = '*') {

    global $pbr_list;

    require_once("parameter-based-replacement-include-js.php");

    $pbr_list[] = array(
        'parameter' => $parameter,
        'value' => $value,
        'search' => $search,
        'replace' => $replace
    );


}