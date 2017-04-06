<?php

global $tkTwig;

$tkTwig->addFilter("wcthumbnail", function ($item, $size = "post-thumbnail") {
    if ($item instanceof WC_Product) {
        return tkGetImageForProduct($size, $item->post);
    }
    throw new Exception("Type not supported!");
});

$tkTwig->addFilter("wcisinbasket", function ($item) {
    if ($item instanceof WC_Product) {
        foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
            $_product = $values['data'];

            if ($item->id == $_product->id) {
                return true;
            }
        }
        return false;
    }
    throw new Exception("Type not supported!");
});

$tkTwig->addFilter("wcid", function ($item) {
    if ($item instanceof WC_Product) {
        return $item->id;
    }
    throw new Exception("Type not supported!");
});

$tkTwig->addFilter("wccontent", function ($item) {
    if ($item instanceof WC_Product) {
        return $item->post->post_content;
    }
    throw new Exception("Type not supported!");
});

$tkTwig->addFilter("wctitle", function ($item) {
    if ($item instanceof WC_Product) {
        return $item->get_title();
    }
    print_a($item);

    throw new Exception("Type not supported!");
});


$tkTwig->addFilter("wcurl", function ($item) {
    if ($item instanceof WC_Product) {
        return get_permalink($item->post);
    }
    print_a($item);
    throw new Exception("Type not supported!");
});

$tkTwig->addFilter("wcprice", function ($item, $regular = false) {
    if ($item instanceof WC_Product_Variable) {
        return wc_price($regular ? $item->get_variation_regular_price("min", true) : $item->get_variation_price("min", true));
    }
    if ($item instanceof WC_Product) {
        return wc_price($regular ? $item->get_regular_price() : $item->get_price());
    }
});

$tkTwig->addFilter("isvariable", function ($item) {
    return $item instanceof WC_Product_Variable;
});

$tkTwig->addFilter("sortByMeta", function ($item, $meta, $order = "DESC") {
    $order == 'ASC' ? $order = SORT_ASC : $order = SORT_DESC;
    return tkSortArrayByMetaField($item, $meta, $order);
});