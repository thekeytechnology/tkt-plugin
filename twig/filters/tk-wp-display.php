<?php

function tkAddWpDisplayFilter(TkTemplate $tkTwig)
{


    $tkTwig->addFilter("linebreaks", function ($item) {
        $item = str_replace("\r\n", "<br/>", $item);
        return preg_replace("/\r|\n/", "<br/>", $item);
    });

    $tkTwig->addFilter("commas", function ($item) {
        $item = str_replace("\r\n", ", ", $item);
        return preg_replace("/\r|\n/", ", ", $item);
    });

    $tkTwig->addFilter("shorten", function ($item, $maxLetters = 28) {
        if (mb_strlen($item) > $maxLetters) {
            return mb_substr($item, 0, $maxLetters) . '...'; // change 50 to the number of characters you want to show
        }
        return $item;
    });

    $tkTwig->addFilter("toOptions", function ($items, $selected = NULL, $placeholder = NULL) {
        $options = "";
        print_a($selected);

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

    $tkTwig->addFilter("wpshortcode", function ($code) {
        return do_shortcode($code);
    });

}

global $tkTwig;
tkAddWpDisplayFilter($tkTwig);