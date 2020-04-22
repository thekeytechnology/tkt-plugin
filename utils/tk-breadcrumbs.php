<?php

function tkBreadcrumbs($items, $args = array()) {
    $containerClass = s("container_class", $args, "");
    $script = tkGetBreadcrumbStructuredData($items);

    return $script . '<div class="tk-breadcrumbs ' . $containerClass . '" xmlns:v="http://rdf.data-vocabulary.org/#">' . tkBreadcrumbItems($items, $args, 0) . '</div>';
}

function tkGetBreadcrumbStructuredData($items) {

    $itemListElement = [];

    $index = 1;
    $domain = get_home_url();

    foreach ($items as $item) {

        $url = $item['url'];
        if (strpos($url, $domain) !== 0) {
            $url = $domain . $url;
        }

        $itemListElement[] = array(
            "@type" => "ListItem",
            "position" => $index,
            "name" => $item["name"],
            "item" => $url
        );



        $index++;
    }

    $structuredData = array(
        "@context" => "https://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => $itemListElement
    );

    $json = json_encode($structuredData);

    return '<script type="application/ld+json">' . $json . '</script>';
}


function tkBreadcrumbItems($items, $args = array(), $index = 0) {
    if ($index >= sizeof($items)) {
        return "";
    }

    $entry = $items[$index];
    $name = $entry["name"];
    $url = $entry["url"];

    $isLastItem = $index == sizeof($items) - 1;

    $linkClass = s("link_class", $args, "");

    $link = $isLastItem || empty($url) ? "<span class='$linkClass'>$name</span><link href='$url'/>" : "<a href='$url' class='$linkClass'>$name</a>";

    $separatorIcon = s("separator", $args, "&gt;");

    $separator = $index == 0 ? "" : "<span class='tk-separator'>$separatorIcon</span>";
    $itemClass = $isLastItem ? " tk-last" : "";
    $itemClass = $index == 0 ? " tk-first $itemClass" : $itemClass;

    $breadcrumbClass = s("item_class", $args, "tk-breadcrumb");

    $beforeFirstItem = $index == 0 ? s("before_first_item", $args, "") : "";

    return "$beforeFirstItem$separator<span class='$breadcrumbClass$itemClass'> $link" . tkBreadcrumbItems($items, $args, ++$index) . "</span>";
}
