<?php

global $tkTwig;

$tkTwig->addFilter("wpcount", function ($item) {
    if ($item instanceof WP_Term) {
        return $item->count;
    }
    return 0;
});

$tkTwig->addFilter("wphasposts", function ($item) {
    if ($item instanceof WP_Term) {
        return $item->count > 0;
    }
    return false;
});



$tkTwig->addFilter("taxonomyList", function ($item) {
    return tkWpTaxonomyList($item);
});


$tkTwig->addFilter("wpsubterms", function (WP_Term $term, $orderField = NULL) {
   return tkWpGetSubterms($term, $orderField);
});



$tkTwig->addFilter("wpHasSubterms", function (WP_Term $term, $orderField = NULL) {
    $args = array(
        "taxonomy" => "tk-media-category",
        "parent" => $term->term_id,
        "hide_empty" => false
    );

    if (isset($orderField)) {
        $data['meta_key'] = $orderField;
        $data["orderby"] = "meta_value_num";
        $data["order"] = "ASC";
    }

    return get_terms($args);
});



$tkTwig->addFilter("wptermposts", function ($item, $postType, $taxonomyName, $order = NULL) {
    if ($item instanceof WP_Term) {
        $args = array(
            'posts_per_page' => 50,
            'post_type' => $postType,
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomyName,
                    'field' => 'term_id',
                    'include_children' => false,
                    'terms' => $item->term_id
                )
            )
        );
        if (isset($order)) {
            $args["meta_key"] = $order;
            $args["orderby"] = "meta_value_num";
            $args["order"] = "ASC";
        }

        return get_posts($args);
    }
    return [];
});