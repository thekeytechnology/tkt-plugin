<?php

function tkAddDisplayFilters(TkTemplate $templateEngine) {
    $templateEngine->addFilter("linebreaks", function ($item) {
        $item = str_replace("\r\n", "<br/>", $item);
        return preg_replace("/\r|\n/", "<br/>", $item);
    });

    $templateEngine->addFilter("commas", function ($item) {
        $item = str_replace("\r\n", ", ", $item);
        return preg_replace("/\r|\n/", ", ", $item);
    });

    $templateEngine->addFilter("shorten", function ($item, $maxLetters = 28) {
        if (mb_strlen($item) > $maxLetters) {
            return mb_substr($item, 0, $maxLetters) . '...'; // change 50 to the number of characters you want to show
        }
        return $item;
    });

    $templateEngine->addFilter("toOptions", function ($items, $selected = NULL, $placeholder = NULL, $useId = false) {
        $options = "";

        $optionWasSelected = false;
        foreach ($items as $item) {
            $itemName = $useId ? tkWpId($item) : tkWpName($item);
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

    $templateEngine->addFilter("printa", function ($item) {
        print_a($item);
    });

    $templateEngine->addFilter("wpshortcode", function ($code) {
        return do_shortcode($code);
    });
}

global $tkTwig;
tkAddDisplayFilters($tkTwig);
