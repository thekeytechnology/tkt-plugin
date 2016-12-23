<?php

global $tkTwig;

$tkTwig->addFilter("wpname", function ($item) {
    return tkWpName($item);
});

$tkTwig->addFilter("wptitle", function ($item) {
    return tkWpTitle($item);
});

$tkTwig->addFilter("wpmeta", function ($item, $metaKey, $single = true) {
    return tkWpMeta($item, $metaKey, $single);
});

$tkTwig->addFilter("linebreaks", function ($item) {
    $item = str_replace("\r\n", "<br/>", $item);
    return preg_replace("/\r|\n/", "<br/>", $item);
});

$tkTwig->addFilter("wpurl", function ($item) {
    return tkWpUrl($item);
});

$tkTwig->addFilter("taxonomyList", function ($item) {
    return tkWpTaxonomyList($item);
});

$tkTwig->addFilter("wpid", function ($item) {
    return tkWpId($item);
});

$tkTwig->addFilter("wpthumbnail", function ($item, $size = "post-thumbnail") {
    if ($item instanceof WP_Post) {
        return get_the_post_thumbnail($item, $size);
    }
    return "";
});

$tkTwig->addFunction("wpterms", function ($taxonomyName) {
    return get_terms(array(
        "taxonomy" => $taxonomyName,
        "hide_empty" => false,
        "orderby" => "meta_value_num",
        "meta_key" => "tk-order"
    ));
});

$tkTwig->addFilter("toOptions", function ($items, $selected = NULL, $placeholder = NULL) {
    $options = "";
    print_a($selected);

    $optionWasSelected = false;
    foreach ($items as $item) {
        $itemName = tkWpName($item);
        $itemTitle = tkWpTitle($item);

        $isSelected = $selected == $itemName;
        if ($isSelected) {
            $optionWasSelected = true;
        }
        $selectedAttribute = $isSelected ? "selected" : "";
        $options .= "<option value='$itemName' $selectedAttribute>$itemTitle</option>";
    }

    if (!empty($placeholder)) {
        $defaultSelected = $optionWasSelected ? "" : "selected";
        $options = "<option disabled $defaultSelected value>$placeholder</option>" . $options;
    }

    return $options;
});