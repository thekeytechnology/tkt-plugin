<?php

if ( ! function_exists( 'is_ajax' ) ) {

    /**
     * is_ajax - Returns true when the page is loaded via ajax.
     * @return bool
     */
    function is_ajax() {
        return defined( 'DOING_AJAX' );
    }
}

function tkInitGlobals()
{
    global $tkTwig;

    $tkTwig->addGlobal("wpuserloggedin", is_user_logged_in());
    $tkTwig->addGlobal("wplogouturl", wp_logout_url("/"));
    $tkTwig->addGlobal("wpisajax", is_ajax());
    $tkTwig->addGlobal("wpcurrentpath", add_query_arg(NULL, NULL));
    $tkTwig->addGlobal("wpshoulddisplayrecaptcha", TK_RECAPTCHA);
}

add_action('init', 'tkInitGlobals');


