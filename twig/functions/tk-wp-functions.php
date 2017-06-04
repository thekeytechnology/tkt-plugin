<?php

function tkAddWpFunctions(TkTemplate $tkTwig) {
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

    $tkTwig->addFunction("wpcalluserfunc", function ($name, $parameter = null, $_ = null) {

        return call_user_func($name, $parameter, $_);
    });
}

global $tkTwig;
tkAddWpFunctions($tkTwig);