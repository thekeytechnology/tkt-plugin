<?php

function tkAddUtilFilters(TkTemplate $tkTwig)
{
    $tkTwig->addFilter("isNumeric", function ($item) {
        return is_numeric($item);
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