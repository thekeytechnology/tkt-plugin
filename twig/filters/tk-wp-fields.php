<?php

function tkAddWpFieldFilters(TkTemplate $tkTwig)
{
    $tkTwig->addFilter("wpname", function ($item) {
        return tkWpName($item);
    });

    $tkTwig->addFilter("wptitle", function ($item) {
        return tkWpTitle($item);
    });

    $tkTwig->addFilter("wpauthor", function (WP_Post $item) {
        $authorId = $item->post_author;
        return get_user_by("ID", $authorId);
    });

    $tkTwig->addFilter("wpexcerpt", function (WP_Post $item) {
        return $item->post_excerpt;
    });

    $tkTwig->addFilter("wppostdate", function (WP_Post $item) {
        return $item->post_date;
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

    $tkTwig->addFilter("wprawcontent", function ($item) {
        return tkWpRawContent($item);
    });

    $tkTwig->addFilter("wpid", function ($item) {
        return tkWpId($item);
    });

    $tkTwig->addFIlter("wpattachmentalt", function($attachmentOrId) {
        $attachment = wp_prepare_attachment_for_js($attachmentOrId);
        return $attachment["alt"];
    });

    $tkTwig->addFilter("wpthumbnail", function ($item, $size = "post-thumbnail") {
        if ($item instanceof WP_Post) {
            return get_the_post_thumbnail($item, $size);
        } else if ($item instanceof WC_Product_Variable) {
            return tkGetImageForProduct($size, $item->post);
        }
        return "";
    });

    $tkTwig->addFilter("wpthumbnailurl", function ($item, $size = "post-thumbnail") {
        if ($item instanceof WP_Post) {
            return get_the_post_thumbnail_url($item, $size);
        }
        return "";
    });

    $tkTwig->addFilter("wphasthumbnail", function ($item, $size = "post-thumbnail") {
        if ($item instanceof WP_Post) {
            return has_post_thumbnail($item);
        }
        return false;
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


    $tkTwig->addFilter("wpattachmentimage", function ($item, $size = "post-thumbnail") {
        return tkWpApplyWithId($item, function ($id) use ($size) {
            return wp_get_attachment_image($id, $size);
        }, function ($id) use ($size) {
            return wp_get_attachment_image($id, $size);
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

    $tkTwig->addFilter("wpnickname", function (WP_User $item) {
        return $item->nickname;
    });

    $tkTwig->addFilter("wpemail", function (WP_User $item) {
        return $item->user_email;
    });

    $tkTwig->addFilter("wpdisplayname", function (WP_User $item) {
        return $item->display_name;
    });


    $tkTwig->addFilter("wpVideoThumbnail", function ($videoCode) {
        return tkGetVideoThumbnailPathFromVideoCode($videoCode);
    });
}