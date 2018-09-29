<?php

/* Returns an item from array. If key is not set, returns default. */
function s($key, $array, $default = NULL)
{
    return isset($array[$key]) ? $array[$key] : $default;
}

/* Returns an item[0] from array. If key is not set, returns default. */
function s0($key, $array, $default = NULL)
{
    return isset($array[$key]) && isset($array[$key][0]) ? $array[$key][0] : $default;
}

function removeFromArray(&$haystack, $needle)
{
    $key = array_search($needle, $haystack);
    if ($key !== false) {
        unset($haystack[$key]);
    }
}

function tkRandomPassword($len = 8)
{
    /* Programmed by Christian Haensel
    ** christian@chftp.com
    ** http://www.chftp.com
    **
    ** Exclusively published on weberdev.com.
    ** If you like my scripts, please let me know or link to me.
    ** You may copy, redistribute, change and alter my scripts as
    ** long as this information remains intact.
    **
    ** Modified by Josh Hartman on 12/30/2010.
    */
    if (($len % 2) !== 0) { // Length paramenter must be a multiple of 2
        $len = 8;
    }
    $length = $len - 2; // Makes room for the two-digit number on the end
    $conso = array('b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z');
    $vocal = array('a', 'e', 'i', 'o', 'u');
    $password = '';
    srand((double)microtime() * 1000000);
    $max = $length / 2;
    for ($i = 1; $i <= $max; $i++) {
        $password .= $conso[rand(0, 19)];
        $password .= $vocal[rand(0, 4)];
    }
    $password .= rand(10, 99);
    $newpass = $password;
    return $newpass;
}


if (!function_exists('tkWriteLog')) {
    function write_log($log)
    {
        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }
}

function print_a($item)
{
    print "<pre>" . print_r($item, true) . "</pre>";
}

function tkAddQueryParams($url, $newParams)
{
    $url_parts = parse_url($url);
    if (isset($url_parts["query"])) {
        parse_str($url_parts['query'], $params);
    } else {
        $params = array();
    }

    $mergedParams = array_merge($params, $newParams);

    $url_parts['query'] = http_build_query($mergedParams);

    return $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'] . '?' . $url_parts['query'];
}


function tkBreadcrumbs($items, $args = array())
{
    $containerClass = s("container_class", $args, "");
    return '<div class="tk-breadcrumbs ' . $containerClass . '" xmlns:v="http://rdf.data-vocabulary.org/#">' . tkBreadcrumbItems($items, $args, 0) . '</div>';
}

function tkBreadcrumbItems($items, $args = array(), $index = 0)
{
    if ($index >= sizeof($items)) {
        return "";
    }
    $type = $index == 0 ? "typeof='v:Breadcrumb'" : "rel='v:child' typeof='v:Breadcrumb'";

    $entry = $items[$index];
    $name = $entry["name"];
    $url = $entry["url"];

    $isLastItem = $index == sizeof($items) - 1;

    $linkClass = s("link_class", $args, "");

    $link = $isLastItem ? "<span class='$linkClass'>$name</span>" : "<a href='$url' class='$linkClass' rel='v:url' property='v:title'>$name</a>";

    $separatorIcon = s("separator", $args, "&gt;");

    $separator = $index == 0 ? "" : "<span class='tk-separator'>$separatorIcon</span>";
    $itemClass = $isLastItem ? " tk-last" : "";
    $itemClass = $index == 0 ? " tk-first $itemClass" : $itemClass;

    $breadcrumbClass = s("item_class", $args, "tk-breadcrumb");

    $beforeFirstItem = $index == 0 ? s("before_first_item", $args, "") : "";

    return "$beforeFirstItem$separator<span class='$breadcrumbClass$itemClass' $type>$link" . tkBreadcrumbItems($items, $args, ++$index) . "</span>";
}

function tkGetWPRootPath()
{
    $home = set_url_scheme(get_option('home'), 'http');
    $siteurl = set_url_scheme(get_option('siteurl'), 'http');
    if (!empty($home) && 0 !== strcasecmp($home, $siteurl)) {
        $wp_path_rel_to_home = str_ireplace($home, '', $siteurl); /* $siteurl - $home */
        $pos = strripos(str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']), trailingslashit($wp_path_rel_to_home));
        $home_path = substr($_SERVER['SCRIPT_FILENAME'], 0, $pos);
        $home_path = trailingslashit($home_path);
    } else {
        $home_path = ABSPATH;
    }

    return str_replace('\\', '/', $home_path);
}


function tkWriteLog($log)
{
    if (is_array($log) || is_object($log)) {
        error_log(print_r($log, true));
    } else {
        error_log($log);
    }
}

function tkHasValidCaptcha($secret)
{
    //get verify response data
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
    $responseData = json_decode($verifyResponse);
    return $responseData->success;
}

function tkGetVideoThumbnailPathFromVideoCode($videocode) {

    if (is_numeric($videocode)) {
        $vimeoMeta="http://vimeo.com/api/v2/video/" . $videocode . ".php";
        $hash = unserialize(file_get_contents($vimeoMeta));
        return $hash[0]['thumbnail_large'];
    } else {
        return 'https://img.youtube.com/vi/' . $videocode . '/hqdefault.jpg';
    }

}

function tkGetURLParameter($paramName, $method="", $default="")
{
    if ($method == "GET"){
        if (isset($_GET[$paramName])) {
            return $_GET[$paramName];
        }
    } elseif ($method == "POST"){
        if(isset($_POST[$paramName])) {
            return $_POST[$paramName];
        }
    } else {
        if (isset($_GET[$paramName])) {
            return $_GET[$paramName];
        }
        if (isset($_POST[$paramName])) {
            return $_POST[$paramName];
        }
    }
    return $default;
}

function tkIsProbablyAdwords()
{
    return tkGetURLParameter("gclid", "GET") ? true : false;
}


function tkOutputFilters($filter)
{
    global $wp_filter;
    echo '<pre>';
    var_dump($wp_filter[$filter]);
    echo '</pre>';
}