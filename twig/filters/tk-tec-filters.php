<?php

function tkTecEventTitle(WP_Post $event)
{
    return $event->post_title . " (" . tribe_get_start_date($event, true, "d.m.Y - H:i") . ")";
}

function tkAddTecFilters(TkTemplate $tkTwig)
{
    $tkTwig->addFilter("tecstartdate", function ($event, $format) {
        return tribe_get_start_date($event, true, $format);
    });

    $tkTwig->addFilter("teceventtitle", function ($event) {
        return tkTecEventTitle($event);
    });
}
