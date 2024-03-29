<?php

/**
 * @param string $target address the masked link should redirect to
 * @param string $classes classes to be added to the link
 * @param string $title text value for the input (this is the clickable text that will act like a link)
 * @return string
 */
function tkCreatePRGForm($href, $classes, $title, $target) {
    if (is_array($classes)) {
        $classes = implode(' ', $classes);
    }
    $html = "<form action='/wp-content/plugins/tkt-plugin/prg-pattern/tk-prg.php' method='post' target='$target' class='$classes tk-prg-form'>
        <input type='submit' class='tk-prg-input' value='$title'/>
        <input type='hidden' name='tk-target' value='$href' />
    </form>";
    return $html;
}

function tkPRGShortcode($atts) {
    $href = isset($atts['href']) ? $atts['href'] : "";
    $title = isset($atts['title']) ? $atts['title'] : "";
    $target = isset($atts['target']) ? $atts['target'] : "";
    $classes = isset($atts['classes']) ? $atts['classes'] : array();

    return tkCreatePRGForm($href, $classes, $title, $target);
}

if (function_exists('add_shortcode')) {
    add_shortcode('tk-prg', 'tkPRGShortcode');
}



function tkApplyPrgMaskingToNavLinks($item_output, $item, $depth, $args) {

    preg_match_all('/<a[^>]+rel=([\'"])(?<rel>.+?)\1[^>]*>/i', $item_output, $rel);

    if ($rel && isset($rel['rel']) && isset($rel['rel'][0]) && $rel['rel'][0] == 'prg') {
        preg_match_all('/<a[^>]+class=([\'"])(?<class>.+?)\1[^>]*>/i', $item_output, $class);
        if (isset($class['class']) && isset($class['class'][0])) {
            $class = $class['class'][0];
        } else {
            $class = "";
        }


        preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $item_output, $href);
        if (isset($href['href']) && isset($href['href'][0])) {
            $href = $href['href'][0];
        } else {
            $href = "";
        }

        preg_match_all('/<a[^>]+target=([\'"])(?<target>.+?)\1[^>]*>/i', $item_output, $target);
        if (isset($target['target']) && isset($target['target'][0])) {
            $target = $target['target'][0];
        } else {
            $target = "";
        }

        preg_match('/<a ?.*>(.*)<\/a>/', $item_output, $title);
        if (isset($title[1])) {
            $title =$title[1];
        } else {
            $title = "";
        }

        preg_match('/(<a ?.*>?.*<\/a>)/', $item_output, $fullLink);
        if (isset($fullLink[1])) {
            $fullLink = $fullLink[1];
        } else {
            $fullLink = "";
        }

        if ($href) {
            $form = tkCreatePRGForm($href, $class, $title, $target);
            $item_output = str_replace($fullLink, $form, $item_output);
        }


    }

    return $item_output;
}


function tkInstallPrgLinkMasking() {
    if (function_exists('add_filter')) {
        add_filter('walker_nav_menu_start_el', 'tkApplyPrgMaskingToNavLinks', 10, 4);
    }
}
