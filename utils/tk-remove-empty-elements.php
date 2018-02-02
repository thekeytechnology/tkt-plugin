<?php
function tkRemoveEmptyElements($content)
{
    // clean up p tags around block elements
    $content = preg_replace(array(
        '#<p>\s*<(div|aside|section|article|header|footer)#',
        '#</(div|aside|section|article|header|footer)>\s*</p>#',
        '#</(div|aside|section|article|header|footer)>\s*<br ?/?>#',
        '#<(div|aside|section|article|header|footer)(.*?)>\s*</p>#',
        '#<p>\s*</(div|aside|section|article|header|footer)#',
    ), array(
        '<$1',
        '</$1>',
        '</$1>',
        '<$1$2>',
        '</$1',
    ), $content);

    //remove errant p tags at the beginning or end
    $content = preg_replace(array(
        "#^\s*</p>#",
        "#<p>\s*$#"
    ), array(
        "",
        ""
    ), $content);

    return preg_replace('#<p>(\s|&nbsp;)*+(<br\s*/*>)*(\s|&nbsp;)*</p>#i', '', $content);
}

function tkInstallRemoveEmptyElements() {
    add_filter('the_content', 'tkRemoveEmptyElements', 20, 1);
}