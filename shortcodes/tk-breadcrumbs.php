<?php

//TODO check whether the non-page ones also need fixing

function tkDefaultBreadcrumbs($rootName, $queriedObject, $args = array())
{

    $breadcrumbs = array(
        array("name" => $rootName, "url" => "/")
    );

    $breadcrumbsPart = array(); //non-root part; seperate so it can be reversed easily


    /* Blog Post */
    if ($queriedObject instanceof WP_Post and $queriedObject->post_type == "post") {

        $blogBreadcrumb = tkGetBlogBreadcrumb($queriedObject);
        if ($blogBreadcrumb) {
            $breadcrumbs[] = $blogBreadcrumb;
        }

        $categoryBreadcrumb = tkGetPrimaryCategoryBreadcrumb($queriedObject, "category");
        if ($categoryBreadcrumb) {
            $breadcrumbs[] = $categoryBreadcrumb;
        }

        $breadcrumbs[] = array(
            "name" => $queriedObject->post_title,
            "url" => get_permalink($queriedObject->ID)
        );

        /* Blog Category */
    } else if ($queriedObject instanceof WP_Term and $queriedObject->taxonomy == "category") {

        $blogBreadcrumb = tkGetBlogBreadcrumb($queriedObject);
        if ($blogBreadcrumb) {
            $breadcrumbs[] = $blogBreadcrumb;
        }

        $breadcrumbs[] = array(
            "name" => apply_filters('tk_breadcrumbs_blog_category_prefix', "Kategorie: ") . $queriedObject->name,
            "url" => apply_filters('tk_breadcrumbs_blog_category_url', get_term_link($queriedObject->term_id))
        );
        /* Blog Tag */
    } else if ($queriedObject instanceof WP_Term and $queriedObject->taxonomy == "tag") {

        $blogBreadcrumb = tkGetBlogBreadcrumb($queriedObject);
        if ($blogBreadcrumb) {
            $breadcrumbs[] = $blogBreadcrumb;
        }

        $breadcrumbs[] = array(
            "name" => apply_filters('tk_breadcrumbs_blog_tag_prefix', "Schlagwort: ") . $queriedObject->name,
            "url" => apply_filters('tk_breadcrumbs_blog_tag_url', get_term_link($queriedObject->term_id))
        );
        /* Product */
    } else if ($queriedObject instanceof WP_Post and $queriedObject->post_type == "product") {

        $shopBreadcrumb = tkGetShopBreadcrumb($queriedObject);
        if ($shopBreadcrumb) {
            $breadcrumbs[] = $shopBreadcrumb;
        }

        $categoryBreadcrumb = tkGetPrimaryCategoryBreadcrumb($queriedObject, "product_cat");
        if ($categoryBreadcrumb) {
            $breadcrumbs[] = $categoryBreadcrumb;
        }

        $breadcrumbs[] = array(
            "name" => $queriedObject->post_title,
            "url" => get_permalink($queriedObject->ID)
        );
        /* Product Category */
    } else if ($queriedObject instanceof WP_Term and $queriedObject->taxonomy == "product_cat") {

        $shopBreadcrumb = tkGetShopBreadcrumb($queriedObject);
        if ($shopBreadcrumb) {
            $breadcrumbs[] = $shopBreadcrumb;
        }

        $breadcrumbs[] = array(
            "name" => apply_filters('tk_breadcrumbs_product_category_prefix', "Kategorie: ") . $queriedObject->name,
            "url" => get_term_link($queriedObject->term_id)
        );
        /* Product Tag */
    } else if ($queriedObject instanceof WP_Term and $queriedObject->taxonomy == "product_tag") {

        $shopBreadcrumb = tkGetShopBreadcrumb($queriedObject);
        if ($shopBreadcrumb) {
            $breadcrumbs[] = $shopBreadcrumb;
        }

        $breadcrumbs[] = array(
            "name" => apply_filters('tk_breadcrumbs_product_tag_prefix', "Schlagwort: ") . $queriedObject->name,
            "url" => get_term_link($queriedObject->term_id)
        );
        /* 404 */
    } else if (is_404()) {
        $breadcrumbs[] = array(
            "name" => apply_filters('tk_breadcrumbs_404', "404 - nicht gefunden"),
            "url" => apply_filters('tk_breadcrumbs_404_url', "")
        );
        /* Search */
    } else if (is_search()) {
        $searchterm = $_GET['s'];
        $breadcrumbs[] = array(
            "name" => apply_filters('tk_breadcrumbs_search_prefix', "Suchergebnisse für: ") . $searchterm,
            "url" => "?s=" . $searchterm
        );
        /* Default for pages, custom post types */
    } elseif ($queriedObject instanceof WP_Post) {
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
        /* Default for everything else */
    } else {
        $breadcrumbs[] = array(
            "name" => tkWpTitle($queriedObject),
            "url" => tkWpUrl($queriedObject)
        );
    }

    $breadcrumbs = apply_filters('tk_breadcrumbs_array', $breadcrumbs);
    $args = apply_filters('tk_breadcrumbs_args', $args);

    return tkBreadcrumbs($breadcrumbs, $args);
}


function tkGetShopBreadcrumb($queriedObject) {
    $breadcrumb = false;
    $shopId = wc_get_page_id( 'shop' );
    if ($shopId) {
        $breadcrumb = array(
            "name" => apply_filters('tk_breadcrumbs_shop_name', get_the_title($shopId)),
            "url" => apply_filters('tk_breadcrumbs_shop_url', get_permalink($shopId))
        );
    } elseif (has_filter("tk_breadcrumbs_shop_name") || has_filter("tk_breadcrumbs_shhop_url")) {
        $breadcrumb = array(
            "name" => apply_filters('tk_breadcrumbs_shop_name', "Shop"),
            "url" => apply_filters('tk_breadcrumbs_shop_url', "/shop/"),
        );
    }

    return $breadcrumb;
}

function tkGetBlogBreadcrumb($queriedObject) {
    $breadcrumb = false;

    $blogPage = get_option( 'page_for_posts' );
    if ($blogPage) {
        $breadcrumb = array(
            "name" => apply_filters('tk_breadcrumbs_blog_name', get_the_title($blogPage)),
            "url" => apply_filters('tk_breadcrumbs_blog_url', get_permalink($blogPage))
        );
    } elseif (has_filter("tk_breadcrumbs_blog_name") || has_filter("tk_breadcrumbs_blog_url")) {
        $breadcrumb = array(
            "name" => apply_filters('tk_breadcrumbs_blog_name', "Blog"),
            "url" => apply_filters('tk_breadcrumbs_blog_url', "/blog/")
        );
    }
    return $breadcrumb;
}

function tkGetPrimaryCategoryBreadcrumb($queriedObject, $taxonomy) {
    if (class_exists("WPSEO_Primary_Term")) {
        $termObject = new WPSEO_Primary_Term($taxonomy, $queriedObject->ID);
        $firstCategory = $termObject->get_primary_term();
    } else {
        $terms = get_the_terms($queriedObject->ID, $taxonomy);
        $firstCategory = s(0, $terms);
    }

    $breadcrumb = false;
    if ($firstCategory) {
        $breadcrumb = array(
            "name" => apply_filters('tk_breadcrumbs_blog_' . $taxonomy . '_prefix', "Kategorie: ") . $firstCategory->name,
            "url" => apply_filters('tk_breadcrumbs_blog_' . $taxonomy . '_url', get_term_link($firstCategory->term_id))
        );
    }

    return $breadcrumb;
}
