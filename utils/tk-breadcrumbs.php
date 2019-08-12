<?php

function tkBreadcrumbs($items, $args = array())
{
    $containerClass = s("container_class", $args, "");
    return '<div class="tk-breadcrumbs ' . $containerClass . '" xmlns:v="http://rdf.data-vocabulary.org/#">' . tkBreadcrumbItems($items, $args, 0) . '</div>';
}

function tkBreadcrumbItems($items, $args = array(), $index = 0)
{
    if ($index >= sizeof($items)) {
        return "";
    }
    $type = $index == 0 ? "typeof='v:Breadcrumb'" : "rel='v:child' typeof='v:Breadcrumb'";

    $entry = $items[$index];
    $name = $entry["name"];
    $url = $entry["url"];

    $isLastItem = $index == sizeof($items) - 1;

    $linkClass = s("link_class", $args, "");

    $link = $isLastItem || empty($url)  ? "<span class='$linkClass' property='v:title'>$name</span><link property='v:url' href='$url'/>" : "<a href='$url' class='$linkClass' rel='v:url' property='v:title'>$name</a>";

    $separatorIcon = s("separator", $args, "&gt;");

    $separator = $index == 0 ? "" : "<span class='tk-separator'>$separatorIcon</span>";
    $itemClass = $isLastItem ? " tk-last" : "";
    $itemClass = $index == 0 ? " tk-first $itemClass" : $itemClass;

    $breadcrumbClass = s("item_class", $args, "tk-breadcrumb");

    $beforeFirstItem = $index == 0 ? s("before_first_item", $args, "") : "";

    return "$beforeFirstItem$separator<span class='$breadcrumbClass$itemClass' $type> $link" . tkBreadcrumbItems($items, $args, ++$index) . "</span>";
}