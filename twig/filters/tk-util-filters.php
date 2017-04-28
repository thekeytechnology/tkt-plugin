<?php

global $tkTwig;

$tkTwig->addFilter("isNumeric", function ($item) {
    return is_numeric($item);
});
