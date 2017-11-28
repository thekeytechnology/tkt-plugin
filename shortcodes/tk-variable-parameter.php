<?php

function tkVariableParameter($atts, $defaultContent = null)
{
    $tag = s("tag", $atts, "");
    $parameter = s("parameter", $atts, "campaign");

    $content = $defaultContent;
    if (isset($_GET[$parameter])) {
        $campaign = $_GET[$parameter];
        $content = base64_decode(urldecode($campaign));
    }

    return empty($tag) ? $content : "<$tag>$content</$tag>";
}

add_shortcode("tk-variable-parameter", "tkVariableParameter");