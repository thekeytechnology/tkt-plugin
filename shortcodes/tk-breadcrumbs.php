<?php

//TODO check whether the non-page ones also need fixing

function tkDefaultBreadcrumbs($rootName, $queriedObject)
{

    $breadcrumbs = array(
        array("name" => $rootName, "url" => "/")
    );

    $breadcrumbsPart = array(); //non-root part; seperate so it can be reversed easily

    if ($queriedObject instanceof WP_Post and $queriedObject->post_type == "page") {
        $parent = $queriedObject->post_parent;
        while ($parent) {
            $parentPost = get_post($parent);

            $breadcrumbsPart[] = array(
                "name" => $parentPost->post_title,
                "url" => get_permalink($parentPost->ID)
            );
            $parent = $parentPost->post_parent;
        }

        $breadcrumbsPart = array_reverse($breadcrumbsPart);

        $breadcrumbs = array_merge($breadcrumbs, $breadcrumbsPart);

        $breadcrumbs[] = array(
            "name" => $queriedObject->post_title,
            "url" => get_permalink(get_queried_object_id())
        );

    } else if ($queriedObject instanceof WP_Post and $queriedObject->post_type == "product") {
        $breadcrumbs[] = array(
            "name" => "Shop",
            "url" => "/shop/"
        );

        $terms = get_the_terms($queriedObject->ID, 'product_cat');
        $firstCategory = s(0, $terms);

        if ($firstCategory) {
            $breadcrumbs[] = array(
                "name" => "Kategorie: " . $firstCategory->name,
                "url" => "/kategorie/" . $firstCategory->slug . "/"
            );
        }

        $breadcrumbs[] = array(
            "name" => $queriedObject->post_title,
            "url" => "/shop/" . $queriedObject->post_name . "/"
        );
    } else if ($queriedObject instanceof WP_Term and $queriedObject->taxonomy == "product_cat") {
        $breadcrumbs[] = array(
            "name" => "Shop",
            "url" => "/shop/"
        );
        $breadcrumbs[] = array(
            "name" => "Kategorie: " . $queriedObject->name,
            "url" => "/kategorie/" . $queriedObject->slug . "/"
        );
    } else if ($queriedObject instanceof WP_Term and $queriedObject->taxonomy == "product_tag") {
        $breadcrumbs[] = array(
            "name" => "Shop",
            "url" => "/shop/"
        );
        $breadcrumbs[] = array(
            "name" => "Schlagwort: " . $queriedObject->name,
            "url" => "/schlagwort/" . $queriedObject->slug . "/"
        );
    } else if (is_404()) {
        $breadcrumbs[] = array(
            "name" => "404 - Seite nicht gefunden",
            "url" => ""
        );
    }


    return tkBreadcrumbs($breadcrumbs);
}

