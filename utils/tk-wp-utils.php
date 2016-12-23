<?php

function tkWpTitle($item)
{
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
    }, function ($termId) use ($item) {
        return get_term_link($termId);
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

function tkWpApplyWithId($item, $toPost, $toTerm)
{
    if (empty($item)) {
        return "";
    } else if ($item instanceof WP_Term) {
        return $toTerm($item->term_id);
    } else if ($item instanceof WP_Post) {
        return $toPost($item->ID);
    } else if (is_array($item)) {
        if (isset($item["term_id"])) {
            return $toTerm($item["term_id"], $item["taxonomy"]);
        } else if (isset($item["ID"])) {
            return $toPost($item["ID"], $item["post_type"]);
        }
    }
    throw new Exception("This type is not supported!");
}