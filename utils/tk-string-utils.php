<?php

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

function endsWithIgnoreCase($haystack, $needle)
{
    $haystack = strtolower($haystack);
    $needle = strtolower($needle);
    return endsWith($haystack, $needle);
}

function tkReplaceLinebreaks($text, $replacement="<br/>"){
    $text = str_replace("\r\n", $replacement, $text);
    $text = preg_replace("/\r|\n/", $replacement, $text);
    return $text;
}

function tkShorten($text, $maxLetters = 28, $suffix="..."){
    if (mb_strlen($text) > $maxLetters) {
        return mb_substr($text, 0, $maxLetters) . $suffix;
    }
    return $text;
}