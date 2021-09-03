<?php


/**
 * This does almost the same as the default parse_url, except the returned object will have its query value as another associative array.
 *
 * So instead of query being "par1=val1&par2=val2" you get
 *  {
 *      'par1' => 'val1',
 *      'par2' => 'val2'
 *  }
 *
 *
 * @param $urlString
 * @return mixed
 */
if (!function_exists('tk_parse_url')) {
    function tk_parse_url($urlString) {

        $parsedUrl = parse_url($urlString);

        if (isset($parsedUrl['query']) && !empty($parsedUrl['query'])) {
            $queryPairs = explode('&', $parsedUrl['query']);
            $queryArray = array();

            foreach ($queryPairs as $pair) {
                $kvPair = explode('=', $pair);
                if (isset($kvPair[1])) {
                    $queryArray[$kvPair[0]] = $kvPair[1];
                } else {
                    $queryArray[$kvPair[0]] = "";
                }
            }

            $parsedUrl['query'] = $queryArray;
        }



        return $parsedUrl;

    }
}


/**
 *
 * Reverse Function of tk_parse_url.
 *
 * @param array $parts
 * @return string
 */
if (!function_exists('tk_build_url')) {
    function tk_build_url(array $parts) {
        return (isset($parts['scheme']) ? "{$parts['scheme']}:" : '') .
            ((isset($parts['user']) || isset($parts['host'])) ? '//' : '') .
            (isset($parts['user']) ? "{$parts['user']}" : '') .
            (isset($parts['pass']) ? ":{$parts['pass']}" : '') .
            (isset($parts['user']) ? '@' : '') .
            (isset($parts['host']) ? "{$parts['host']}" : '') .
            (isset($parts['port']) ? ":{$parts['port']}" : '') .
            (isset($parts['path']) ? "{$parts['path']}" : '') .
            (isset($parts['query']) ? tk_query_var_array_to_string($parts['query']) : '') .
            (isset($parts['fragment']) ? "#{$parts['fragment']}" : '');
    }
}

if (!function_exists('tk_query_var_array_to_string')) {
    function tk_query_var_array_to_string($query) {

        $queryString = implode('&', array_map(
            function ($v, $k) {

                if ($k) {
                    if ($v) {
                        return $k.'='.$v;
                    }
                    return $k;
                }
                return "";
            },
            $query,
            array_keys($query)
        ));

        return "?" . $queryString;
    }
}
