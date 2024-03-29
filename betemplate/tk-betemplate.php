<?php

//In BeTheme's Theme Options under Global -> Advanced -> Theme Functions -> Post Type | Disable: Make sure "Templates" is NOT disabled.


// make betheme template not publicly queryable
function tkBeTemplateCPTArgsOverride($args, $post_type)
{
    if ($post_type == 'template') {
        $args['publicly_queryable'] = false;
    }
    return $args;
}

function tkInstallBeTemplate()
{
    require_once("tinymce/tk-tinymce-button-betemplate.php");
    require_once("tk-betemplate-shortcode.php");

    //enable visual editor for betheme template post type
    // (required for proper functionality of visual editor builder element)
    //but hide it because outputting the_content from it is currently unsupported
    add_post_type_support("template", "editor");
    add_action("admin_head", function () {
        echo '<style> .post-php.post-type-template #postdivrich, .post-new-php.post-type-template #postdivrich {display: none !important;} </style>';
    });

    add_filter("register_post_type_args", "tkBeTemplateCPTArgsOverride", 10, 2);
}

/** Adding backwards compatibility to mfn builder because betheme devs are mentally challenged */
function tkMfnBuilderPrint ($post_id, $content_field = false) {
    if (function_exists("mfn_builder_print")) {
        mfn_builder_print($post_id, $content_field);
    } elseif (class_exists("Mfn_Builder_Front")) {
        $mfn_builder = new Mfn_Builder_Front($post_id, $content_field, true);
        $mfn_builder->show();
    }
}

