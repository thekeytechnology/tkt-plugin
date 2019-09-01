<?php

function tkAddElementorFilters (TkTemplate $tkTwig) {

    $tkTwig->addFilter("wpelementorcontent", function (WP_Post $item) {
        return tkElementorContent($item);
    });

    $tkTwig->addFilter("wpcontentdefaultfilter", function (WP_Post $item) {
        return tkElementorWpContent($item);
    });

}




