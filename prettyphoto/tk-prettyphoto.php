<?php
/*

Include the following (with the options of your choice) in the theme:

jQuery(document).ready(function($){
    $("a[rel^='prettyPhoto']").prettyPhoto();
});

*/

function tkInstallPrettyPhoto(){
    add_action("wp_enqueue_scripts", function(){
        wp_enqueue_script('tkPrettyPhotoScript', plugins_url().'/tkt-plugin/prettyphoto/js/jquery.prettyPhoto.js', array('jquery'));
        wp_enqueue_style('tkPrettyPhotoStyle', plugins_url().'/tkt-plugin/prettyphoto/css/prettyPhoto.css');
    }, 11);
}