<?php

//TODO check whether the non-page ones also need fixing

function tkDefaultBreadcrumbs($rootName, $queriedObject, $args = array())
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
            "name" => apply_filters('tk_breadcrumbs_shop_name', "Shop"),
            "url" => apply_filters('tk_breadcrumbs_shop_url', "/shop/"),
        );


        if (class_exists("WPSEO_Primary_Term")) {
            $termObject = new WPSEO_Primary_Term('product_cat', $queriedObject->ID);
            $firstCategory = $termObject->get_primary_term();
        } else {
            $terms = get_the_terms($queriedObject->ID, 'product_cat');
            $firstCategory = s(0, $terms);
        }


        if ($firstCategory) {
            $breadcrumbs[] = array(
                "name" => apply_filters('tk_breadcrumbs_product_category_prefix', "Kategorie: ") . $firstCategory->name,
                "url" => apply_filters('tk_breadcrumbs_product_category_url', "/kategorie/") . $firstCategory->slug . "/"
            );
        }

        $breadcrumbs[] = array(
            "name" => $queriedObject->post_title,
            "url" => apply_filters('tk_breadcrumbs_shop_url', "/shop/") . $queriedObject->post_name . "/"
        );
    } else if ($queriedObject instanceof WP_Term and $queriedObject->taxonomy == "product_cat") {
        $breadcrumbs[] = array(
            "name" => apply_filters('tk_breadcrumbs_shop_name', "Shop"),
            "url" => apply_filters('tk_breadcrumbs_shop_url', "/shop/")
        );
        $breadcrumbs[] = array(
            "name" => apply_filters('tk_breadcrumbs_product_category_prefix', "Kategorie: ") . $queriedObject->name,
            "url" => apply_filters('tk_breadcrumbs_product_category_url', "/kategorie/") . $queriedObject->slug . "/"
        );
    } else if ($queriedObject instanceof WP_Term and $queriedObject->taxonomy == "product_tag") {
        $breadcrumbs[] = array(
            "name" => apply_filters('tk_breadcrumbs_shop_name', "Shop"),
            "url" => apply_filters('tk_breadcrumbs_shop_url', "/shop/")
        );
        $breadcrumbs[] = array(
            "name" => apply_filters('tk_breadcrumbs_product_tag_prefix', "Schlagwort: ") . $queriedObject->name,
            "url" => apply_filters('tk_breadcrumbs_product_tag_url', "/schlagwort/") . $queriedObject->slug . "/"
        );
    } else if (is_404()) {
        $breadcrumbs[] = array(
            "name" => apply_filters('tk_breadcrumbs_404', "404 - nicht gefunden"),
            "url" => ""
        );
    } else if (is_search()) {
        $searchterm = $_GET['s'];
        $breadcrumbs[] = array(
            "name" => apply_filters('tk_breadcrumbs_search_prefix', "Suchergebnisse für: ") . $searchterm,
            "url" => "?s=" . $searchterm
        );
    }


    return tkBreadcrumbs($breadcrumbs, $args);
}

