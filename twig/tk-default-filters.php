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

$tkTwig->addFilter("wpcount", function ($item) {
    if ($item instanceof WP_Term) {
        return $item->count;
    }
    return 0;
});

$tkTwig->addFilter("wpcontent", function ($item) {
    return tkWpContent($item);
});

$tkTwig->addFilter("taxonomyList", function ($item) {
    return tkWpTaxonomyList($item);
});

$tkTwig->addFilter("wpid", function ($item) {
    return tkWpId($item);
});

$tkTwig->addFilter("shorten", function ($item, $maxLetters = 28) {
    if (mb_strlen($item) > $maxLetters) {
        return mb_substr($item, 0, $maxLetters) . '...'; // change 50 to the number of characters you want to show
    }
    return $item;
});

$tkTwig->addFilter("wpshortcode", function ($code) {
    return do_shortcode($code);
});

$tkTwig->addFilter("wpthumbnail", function ($item, $size = "post-thumbnail") {
    if ($item instanceof WP_Post) {
        return get_the_post_thumbnail($item, $size);
    } else if ($item instanceof WC_Product_Variable) {
        return tkGetImageForProduct($size, $item->post);
    }
    return "";
});

$tkTwig->addFilter("wpattachmenturl", function ($item) {
    return tkWpApplyWithId($item, function ($id) {
        return wp_get_attachment_url($id);
    }, function () {
    });
});

$tkTwig->addFilter("wpattachmentimageurl", function ($item, $size = "post-thumbnail") {
    return tkWpApplyWithId($item, function ($id) use ($size) {
        return wp_get_attachment_image_src($id, $size)[0];
    }, function ($id) use ($size) {
        return wp_get_attachment_image_src($id, $size)[0];
    });
});

$tkTwig->addFilter("printa", function ($item) {
    print_a($item);
});

function tkGetImageForProduct($size, $postForImage)
{
    $image_size = apply_filters('single_product_archive_thumbnail_size', $size);
    if (has_post_thumbnail($postForImage)) {
        $props = wc_get_product_attachment_props(get_post_thumbnail_id(), $postForImage);
        return get_the_post_thumbnail($postForImage->ID, $image_size, array(
            'title' => $props['title'],
            'alt' => $props['alt'],
        ));
    } elseif (wc_placeholder_img_src()) {
        return wc_placeholder_img($image_size);
    } else {
        return "";
    }
}

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
        $options = "<option $defaultSelected value>$placeholder</option>" . $options;
    }

    return $options;
});