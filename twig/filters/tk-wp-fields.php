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

$tkTwig->addFilter("wpurl", function ($item) {
    return tkWpUrl($item);
});

$tkTwig->addFilter("wpcontent", function ($item) {
    return tkWpContent($item);
});

$tkTwig->addFilter("wpid", function ($item) {
    return tkWpId($item);
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

$tkTwig->addFilter('wpcommentsenabled', function ($item) {
    if ($item instanceof WP_Post) {
        return comments_open($item);
    }
    return false;
});

$tkTwig->addFilter("wpnonce", function ($param) {
    return wp_nonce_field('ajax-login-nonce', $param, true, false);
});