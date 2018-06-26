<?php

function tkAddWpDisplayFilter(TkTemplate $tkTwig)
{

    $tkTwig->addFilter("linebreaks", function ($item) {
        return tkReplaceLinebreaks($item);
    });

    $tkTwig->addFilter("commas", function ($item) {
        return tkReplaceLinebreaks($item, ", ");
    });

    $tkTwig->addFilter("shorten", function ($item, $maxLetters = 28, $suffix = "...") {
        return tkShorten($item, $maxLetters, $suffix);
    });

    $tkTwig->addFilter("toOptions", function ($items, $selected = NULL, $placeholder = NULL) {
        $options = "";

        $optionWasSelected = false;
        foreach ($items as $item) {
            $itemName = tkWpName($item);
            $itemTitle = tkWpTitle($item);

            $isSelected = $selected == $itemName;
            if ($isSelected) {
                $optionWasSelected = true;
            }
            $selectedAttribute = $isSelected ? "selected" : "";
            $options .= "<option value='$itemName' $selectedAttribute>$itemTitle</option>";
        }

        if (!empty($placeholder)) {
            $defaultSelected = $optionWasSelected ? "" : "selected";
            $options = "<option $defaultSelected value>$placeholder</option>" . $options;
        }

        return $options;
    });

    $tkTwig->addFilter("printa", function ($item) {
        print_a($item);
    });

    $tkTwig->addFilter("removeEmptyElements", function ($item) {
        return tkRemoveEmptyElements($item);
    });

    $tkTwig->addFilter("wpshortcode", function ($code) {
        return do_shortcode($code);
    });

    $tkTwig->addFilter("wptranslate", function ($key, $textdomain) {
        return __($key, $textdomain);
    });

    $tkTwig->addFilter("wpsanitizeemail", function ($email) {
        return sanitize_email($email);
    });

    $tkTwig->addFilter("wpsanitizefilename", function ($filename) {
        return sanitize_file_name($filename);
    });

    $tkTwig->addFilter("wpsanitizehtmlclass", function ($class, $fallback = "") {
        return sanitize_html_class($class, $fallback);
    });

    $tkTwig->addFilter("wpsanitizetextfield", function ($text) {
        return sanitize_text_field($text);
    });

    $tkTwig->addFilter("wpsanitizetextareafield", function ($text) {
        return sanitize_textarea_field($text);
    });

    $tkTwig->addFilter("wpsanitizekey", function ($key) {
        return sanitize_key($key);
    });

    $tkTwig->addFilter("wpescurlraw", function ($url, $protocols = null) {
        return esc_url_raw($url, $protocols);
    });

    $tkTwig->addFilter("wpescurl", function ($url, $protocols = null) {
        return esc_url($url, $protocols);
    });

    $tkTwig->addFilter("wpeschtml", function ($text) {
        return esc_html($text);
    });

    $tkTwig->addFilter("wpescjs", function ($text) {
        return esc_js($text);
    });

    $tkTwig->addFilter("wpescattr", function ($text) {
        return esc_attr($text);
    });

    $tkTwig->addFilter("wpesctextarea", function ($text) {
        return esc_textarea($text);
    });

    $tkTwig->addFilter("wpkses", function ($string, $allowed_html, $allowed_protocols = array()) {
        return wp_kses($string, $allowed_html, $allowed_protocols);
    });

    $tkTwig->addFilter("wpksesnohtml", function ($string) {
        return wp_filter_nohtml_kses($string);
    });

    $tkTwig->addFilter("wpksespost", function ($string) {
        return wp_filter_post_kses($string);
    });
}