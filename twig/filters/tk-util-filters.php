<?php

function tkAddUtilFilters(TkTemplate $tkTwig)
{
    $tkTwig->addFilter("isNumeric", function ($item) {
        return is_numeric($item);
    });
}

