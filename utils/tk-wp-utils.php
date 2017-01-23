<?php

function tkWpTitle($item)
{
    if (is_numeric($item)) {
        $item = get_post($item);
    }

    if (empty($item)) {
        return "";
    } else if ($item instanceof WP_Term) {
        return $item->name;
    } else if ($item instanceof WP_Post) {
        return $item->post_title;
    } else if (is_array($item)) {
        if (isset($item["name"])) {
            return $item["name"];
        } else if (isset($item["post_title"])) {
            return $item["post_title"];
        }
    }
    throw new Exception("This type is not supported!");
}

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

function tkWpName($item)
{
    if (empty($item)) {
        return "";
    } else if ($item instanceof WP_Term) {
        return $item->slug;
    } else if ($item instanceof WP_Post) {
        return $item->post_name;
    } else if (is_array($item)) {
        if (isset($item["name"])) {
            return $item["name"];
        } else if (isset($item["post_name"])) {
            return $item["post_name"];
        }
    }
    throw new Exception("This type is not supported!");
}

function tkWpContent($item)
{
    $content = tkWpRawContent($item);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    return $content;
}

function tkWpRawContent($item)
{
    if (empty($item)) {
        return "";
    } else if ($item instanceof WP_Term) {
        return $item->description;
    } else if ($item instanceof WP_Post) {
        return $item->post_content;
    } else if (is_array($item)) {
        if (isset($item["description"])) {
            return $item["description"];
        } else if (isset($item["post_content"])) {
            return $item["post_content"];
        }
    }
    throw new Exception("This type is not supported!");
}


function tkWpTaxonomyList($items)
{
    if (is_array($items) && !isset($items["ID"]) && !isset($items["term_id"])) {
        $list = "";
        foreach ($items as $item) {
            $list .= "<li>" . tkWpTitle($item) . "</li>";
        }
        return $list;
    } else {
        return "<li>" . tkWpTitle($items) . "</li>";
    }
}

function tkWpMeta($item, $metaKey, $single = true)
{
    return tkWpApplyWithId($item, function ($postId) use ($metaKey, $single) {
        return get_post_meta($postId, $metaKey, $single);
    }, function ($termId) use ($metaKey, $single) {
        return get_term_meta($termId, $metaKey, $single);
    });
}

function tkWpUrl($item)
{
    return tkWpApplyWithId($item, function ($postId) use ($item) {
        return get_permalink($postId);
    }, function ($termId, $taxonomy) use ($item) {
        return get_term_link($termId, $taxonomy);
    });
}

function tkWpId($item)
{
    return tkWpApplyWithId($item,
        function ($postId) {
            return $postId;
        }, function ($termId) {
            return $termId;
        }
    );
}

function tkWpApplyWithId($item, Callable $toPost, Callable $toTerm = NULL)
{
    if (is_numeric($item)) {
        $item = get_post($item);
    }
    if (empty($item)) {
        return "";
    } else if ($item instanceof WP_Term) {
        if (isset($toTerm)) {
            return $toTerm($item->term_id);
        } else {
            throw new Exception("No function provided for this type!");
        }
    } else if ($item instanceof WP_Post) {
        return $toPost($item->ID);
    } else if (is_array($item)) {
        if (isset($item["term_id"])) {
            return $toTerm(intval($item["term_id"]), $item["taxonomy"]);
        } else if (isset($item["ID"])) {
            return $toPost(intval($item["ID"]), $item["post_type"]);
        }
    }
    throw new Exception("This type is not supported!");
}

function tkWpGetSubterms(WP_Term $term, $orderField = NULL)
{
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
}