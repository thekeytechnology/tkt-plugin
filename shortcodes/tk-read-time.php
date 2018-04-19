<?php

add_shortcode("tk-read-time", function () {
    global $post;
    $mycontent = $post->post_content;
    $word = str_word_count(strip_tags($mycontent));
    $m = floor($word / 250);
    $s = floor($word % 250 / (250 / 60));
    $est = $m . ' Minuten' . ', ' . $s . ' Sekunden';
    return "<p>Geschätzte Lesezeit: $est</p>";
});