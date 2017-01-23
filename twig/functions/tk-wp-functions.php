<?php

global $tkTwig;

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

