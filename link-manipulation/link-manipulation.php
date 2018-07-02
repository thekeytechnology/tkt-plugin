<?php

function tkInstallOpenAllLinksInNewTab()
{
    add_action("wp_enqueue_scripts", function () {
        wp_enqueue_script("tk-open-all-links-in-new-tabs", plugins_url() . "/tkt-plugin/link-manipulation/allLinksNewTab.js", array("jquery"));
    }, 11);
}

function tkInstallOpenAllExternalLinksInNewTab()
{
    add_action("wp_enqueue_scripts", function () {
        wp_enqueue_script("tk-open-external-links-in-new-tabs", plugins_url() . "/tkt-plugin/link-manipulation/externalLinksNewTab.js", array("jquery"));
    }, 11);
}

add_shortcode("tkOpenAllLinksInNewTab", function () {
    tkInstallOpenAllLinksInNewTab();
});
