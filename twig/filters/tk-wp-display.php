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

}