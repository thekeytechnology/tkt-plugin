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
    }else if ($item instanceof WP_User) {
        return $item->display_name;
    }  else if (is_array($item)) {
        if (isset($item["name"])) {
            return $item["name"];
        } else if (isset($item["post_title"])) {
            return $item["post_title"];
        } else if (isset($item["display_name"])) {
            return $item["display_name"];
        }
    }
    throw new Exception("This type is not supported! " . print_r($item, true));
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
    } else if ($item instanceof WP_User) {
        return $item->ID;
    } else if (is_array($item)) {
        if (isset($item["name"])) {
            return $item["name"];
        } else if (isset($item["post_name"])) {
            return $item["post_name"];
        }
    }
    throw new Exception("This type is not supported! " . print_r($item, true));
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
    throw new Exception("This type is not supported! " . print_r($item, true));
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
    }, function ($userId) use ($metaKey, $single) {
        return get_user_meta($userId, $metaKey, $single);
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
        }, function ($userId) {
            return $userId;
        }
    );
}

function tkWpType($item)
{
    if ($item instanceof WP_Post) {
        return $item->post_type;
    }
    if ($item instanceof WP_Term) {
        return $item->taxonomy;
    }
    if (is_array($item)) {
        if (isset($item["post_type"])) {
            return $item["post_type"];
        }
        if (isset($item["taxonomy"])) {
            return $item["taxonomy"];
        }
    }
    return false;
}

function tkWpApplyWithId($item, Callable $toPost, Callable $toTerm = NULL, Callable $toUser = NULL)
{
    if (is_numeric($item)) {
        $item = get_post($item);
    }
    if (empty($item)) {
        return "";
    } else if ($item instanceof WP_Term) {
        if (isset($toTerm)) {
            return $toTerm($item->term_id, $item->taxonomy);
        } else {
            throw new Exception("No function provided for this type! " . print_r($item, true));
        }
    } else if ($item instanceof WP_Post) {
        return $toPost($item->ID);
    } else if ($item instanceof WP_User) {
        if (isset($toUser)) {
            return $toUser($item->ID);
        } else {
            throw new Exception("No function provided for this type! " . print_r($item, true));
        }
    } else if (is_array($item)) {
        if (isset($item["term_id"])) {
            return $toTerm(intval($item["term_id"]), $item["taxonomy"]);
        } else if (isset($item["ID"])) {
            return $toPost(intval($item["ID"]), $item["post_type"]);
        }
    }
    throw new Exception("This type is not supported! " . print_r($item, true));
}

function tkWpGetSubterms($taxonomy, WP_Term $parentTerm = NULL, $orderField = NULL)
{
    $args = array(
        "taxonomy" => $taxonomy,
        "hide_empty" => false
    );

    if ($parentTerm != NULL) {
        $args["parent"] = $parentTerm->term_id;
    } else {
        $args["parent"] = NULL;
    }

    if ($orderField != NULL) {
        $args['meta_key'] = $orderField;
        $args["orderby"] = "meta_value_num";
        $args["order"] = "ASC";
    }

    return get_terms($args);
}

function tkSortArrayByMetaField($array, $sortBy, $order = SORT_DESC)
{

    $tempArray = array();

    foreach ($array as $key => $item) {
        if (!isset($item[$sortBy])) {
            $item[$sortBy] = tkWpMeta($item, $sortBy);
        }
        $tempArray[$key] = $item[$sortBy];
    }
    array_multisort($tempArray, $order, $array);
    return $array;
}

function get_the_excerpt_max_charlength($charlength)
{
    $excerpt = get_the_excerpt();
    $charlength++;

    $output = "";
    if (mb_strlen($excerpt) > $charlength) {
        $subex = mb_substr($excerpt, 0, $charlength - 5);
        $exwords = explode(' ', $subex);
        $excut = -(mb_strlen($exwords[count($exwords) - 1]));
        if ($excut < 0) {
            $output .= mb_substr($subex, 0, $excut);
        } else {
            $output .= $subex;
        }
        $output .= '[...]';
    } else {
        return $excerpt;
    }
    return $output;
}