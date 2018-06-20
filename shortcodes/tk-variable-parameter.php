<?php

function tkVariableParameter($atts, $defaultContent = "")
{
    $tag = s("tag", $atts, "");
    $parameter = s("parameter", $atts, "campaign");
    $tag_attributes = s("tag_attributes", $atts, "");

    $content = $defaultContent;
    if ( isset($_GET[$parameter]) && !tkGetURLParameter("nocampaign", "GET", false) ) {
        //The "no campaign" override exists because Adwords sitelink extensions inherit custom parameters from higher levels (e.g. campaigns, adgroups).
        //That can lead to problems if sitelink targets also support the campaign paremeter.
        //E.g. A site about location A is used as sitelink for an ad that leads to a site about location B.
        //Without overriding the campaign parameter, someone clicking the sitelink would see site B with content referring to location A.
        $campaign = $_GET[$parameter];
        $content = base64_decode(urldecode($campaign));
    }

    return empty($tag) ? $content : "<$tag $tag_attributes>$content</$tag>";
}

add_shortcode("tk-variable-parameter", "tkVariableParameter");