<?php
function tkBetemplateEnqueuePluginScripts($plugin_array)
{
    //enqueue TinyMCE plugin script with its ID.
    $plugin_array["betemplate_button_plugin"] =  plugin_dir_url( __FILE__ ) . "button-betemplate.js";
    return $plugin_array;
}

add_filter("mce_external_plugins", "tkBetemplateEnqueuePluginScripts");


function tkBetemplateRegisterButtonsEditor($buttons)
{
    //register buttons with their id.
    array_push($buttons, "betemplate");
    return $buttons;
}

add_filter("mce_buttons", "tkBetemplateRegisterButtonsEditor");
