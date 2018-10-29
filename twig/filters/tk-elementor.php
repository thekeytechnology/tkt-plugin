<?php


function tkAddElementorFilters (TkTemplate $tkTwig) {
    $tkTwig->addFilter("wpelementorcontent", function (WP_Post $item) {
        return tkWpElemntorContent($item);
    });
}

