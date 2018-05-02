<?php

function tkAddUtilFilters(TkTemplate $tkTwig)
{
    $tkTwig->addFilter("isNumeric", function ($item) {
        return is_numeric($item);
    });
    $tkTwig->addFilter("tkToArray", function($item) {
        return (array) $item;
    });

    $tkTwig->addFilter("tkIntersect", function ($array1, $array2) {
        return array_intersect($array1, $array2);
    });

    $tkTwig->addFilter("tkContains", function ($array1, $array2) {;
        return empty(array_intersect($array1, $array2));
    });


}


if (!function_exists('is_ajax')) {

    /**
     * is_ajax - Returns true when the page is loaded via ajax.
     * @return bool
     */
    function is_ajax()
    {
        return defined('DOING_AJAX');
    }
}