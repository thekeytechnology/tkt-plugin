<?php


function tkt_plugin_add_elementor_widgets() {

    // Register Widgets
    require_once ("twig-widget/tk-twig.php");
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new TK_Twig() );

}

if (function_exists("add_action")) {
    add_action( 'elementor/widgets/widgets_registered', 'tkt_plugin_add_elementor_widgets' );
}



function tk_add_elementor_widget_category( $elements_manager ) {

    $elements_manager->add_category(
        'TKT',
        [
            'title' => 'the key technology',
            'icon' => 'fa fa-key',
        ]
    );

}
if (function_exists("add_action")) {
    add_action('elementor/elements/categories_registered', 'tk_add_elementor_widget_category', 10, 1);
}