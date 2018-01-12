<?php


function tkAddPodsFilter(TkTemplate $tkTwig)
{
    $tkTwig->addFilter("wppodsmeta", function ($item, $field, $single = true) {
        $id = tkWpId($item);
        $type = tkWpType($item);

        $pod = pods($type, $id);
        return $pod->field($field, $single);
    });
}