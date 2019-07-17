<?php

/**
 * @param string $target address the masked link should redirect to
 * @param string $classes classes to be added to the link
 * @param string $title text value for the input (this is the clickable text that will act like a link)
 * @return string
 */
function tkCreatePRGForm($href, $classes, $title, $target) {
    $classString = implode(' ', $classes);
    $html = "<form action='/wp-content/plugins/tkt-plugin/prg-pattern/tk-prg.php' method='post' target='$target'>
        <input type='submit' class='$classString tk-prg-input' value='$title'/>
        <input type='hidden' name='tk-target' value='$href' />
    </form>";
    return $html;
}



function tkApplyPrgMaskingToNavLinks($item_output, $item, $depth, $args) {

    preg_match_all('/<a[^>]+rel=([\'"])(?<rel>.+?)\1[^>]*>/i', $item_output, $rel);

    if ($rel && $rel['rel'][0] == 'prg') {
        preg_match_all('/<a[^>]+class=([\'"])(?<class>.+?)\1[^>]*>/i', $item_output, $class);
        $class = $class['class'][0];

        preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $item_output, $href);
        $href = $href['href'][0];

        preg_match_all('/<a[^>]+target=([\'"])(?<target>.+?)\1[^>]*>/i', $item_output, $target);
        $target = $target['target'][0];

        preg_match('/<a ?.*>(.*)<\/a>/', $item_output, $title);
        $title = $title[1];

        preg_match('/(<a ?.*>?.*<\/a>)/', $item_output, $fullLink);
        $fullLink = $fullLink[1];

        $form = tkCreatePRGForm($href, $class, $title, $target);

        $item_output = str_replace($fullLink, $form, $item_output);
    }

    return $item_output;
}


function tkInstallPrgLinkMasking() {
    add_filter('walker_nav_menu_start_el', 'tkApplyPrgMaskingToNavLinks', 10, 4);
}