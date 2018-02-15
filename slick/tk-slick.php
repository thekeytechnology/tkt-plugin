<?php

function tkInstallSlick ($withTheme = true)
{
    add_action("wp_enqueue_scripts", function () use ($withTheme)
    {
        wp_enqueue_script('tkSlickScript', plugins_url().'/tkt-plugin/slick/slick.min.js', array('jquery'));
        wp_enqueue_style('tkSlickStyle', plugins_url().'/tkt-plugin/slick/slick.css');

        if ($withTheme) {
            wp_enqueue_style('tkSlickThemeStyle', plugins_url().'/tkt-plugin/slick/slick-theme.css');
        }
    }, 11);
}