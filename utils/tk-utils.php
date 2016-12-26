<?php

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

function s($key, $array, $default = NULL)
{
    return isset($array[$key]) ? $array[$key] : $default;
}

function s0($key, $array, $default = NULL)
{
    return isset($array[$key]) && isset($array[$key][0]) ? $array[$key][0] : $default;
}

function startsWith($haystack, $needle)
{
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function endsWith($haystack, $needle)
{
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
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


if (!function_exists('write_log')) {
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