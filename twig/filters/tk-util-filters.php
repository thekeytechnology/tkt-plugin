<?php

global $tkTwig;

$tkTwig->addFilter("isNumeric", function ($item) {
    return is_numeric($item);
});

$tkTwig->addFilter("tkIntersect", function ($array1, $array2) {
    return array_intersect($array1, $array2);
});

$tkTwig->addFilter("tkContains", function ($array1, $array2) {;
    return empty(array_intersect($array1, $array2));
});




