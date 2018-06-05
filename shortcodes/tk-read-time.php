<?php

function tkReadTime($minuteWord = "Minuten", $secondWord = "Sekunden", $showSeconds = true)
{
    global $post;
    $postContent = $post->post_content;
    $word = str_word_count(strip_tags($postContent));
    $m = floor($word / 250);
    $s = floor($word % 250 / (250 / 60));
    $output = "$m $minuteWord";
    if ($showSeconds) {
        $output .= ", $s $secondWord";
    }
    return $output;
}

add_shortcode("tk-read-time", function () {
    tkReadTime();
});