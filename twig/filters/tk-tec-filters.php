<?php

function tkAddTecFilters(TkTemplate $tkTwig)
{
    $tkTwig->addFilter("tecstartdate", function ($event, $format) {
        return tribe_get_start_date($event, false, $format);
    });

}
