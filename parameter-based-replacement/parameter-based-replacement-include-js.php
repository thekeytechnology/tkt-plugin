<?php

add_action('wp_enqueue_scripts', 'tkAddPbrScript', 11);

function tkAddPbrScript() {

    global $pbr_list;

    wp_enqueue_script("tk-pbr-script", plugins_url() . "/tkt-plugin/parameter-based-replacement/parameter-based-replacement.js");
    wp_localize_script("tk-pbr-script", "pbr_list", $pbr_list);

}
