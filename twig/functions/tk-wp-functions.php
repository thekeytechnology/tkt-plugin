<?php

function tkAddWpFunctions(TkTemplate $tkTwig)
{
    $tkTwig->addFunction("wppost", function ($postId) {
        return get_post($postId);
    });

    $tkTwig->addFunction("wpterms", function ($taxonomyName) {
        return get_terms(array(
            "taxonomy" => $taxonomyName,
            "hide_empty" => false,
            "orderby" => "meta_value_num",
            "meta_key" => "tk-order"
        ));
    });

    $tkTwig->addFunction('wpcomments', function () {
        comments_template('', true);
    });

    $tkTwig->addFunction("wpcurrentuser", function () {
        return wp_get_current_user();
    });

    $tkTwig->addFunction("wpoption", function ($name, $default = false) {
        return get_option($name, $default);
    });

    $tkTwig->addFunction("wpdynamicsidebar", function ($name) {
        dynamic_sidebar($name);
    });

    $tkTwig->addFunction("wpsearchform", function ($echo = true) {
        get_search_form($echo);
    });

    $tkTwig->addFunction("wpuserby", function ($field, $id) {
        return get_user_by($field, $id);
    });

    //not actually a WP function; remains here for backwards compatibility
    $tkTwig->addFunction("wpcalluserfunc", function ($name, $parameter = null, $_ = null) {
        return call_user_func($name, $parameter, $_);
    });
}
