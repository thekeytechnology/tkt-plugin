<?php

class TK_Twig extends Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'tkTwig';
    }

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return "TK Twig";
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'fa fa-star';
    }

    /**
     * Get widget categories.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'TKT' ];
    }

    /**
     * Register widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'plugin-name' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'tk_twig_name',
            [
                'label' => "Twig Template Name",
                'type' => \Elementor\Controls_Manager::TEXT,
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'tk_twig_parameters',
            [
                'label' => "Parameter",
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'description' => 'JSON Format (key: value, key: value, ...), funktioniert nur mit Strings'
            ]
        );



        $this->end_controls_section();

    }

    /**
     * Render widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        global $tkTwig;

        $settings = $this->get_settings_for_display();

        $name = $settings['tk_twig_name'];
        $parameters= str_replace(array("\r", "\n"), '', $settings['tk_twig_parameters']);
        if ($name) {
            if(!empty($parameters)) {
                $kvStrings = explode(',', $parameters);
                $twigParams = array();
                foreach ($kvStrings as $kvString) {
                    $kvPair = explode(":", $kvString);
                    $twigParams[$kvPair[0]] = trim($kvPair[1]);
                }

                $html = $tkTwig->renderTemplate($name, $twigParams);
            } else {
                $html = $tkTwig->renderTemplate($name);
            }

            echo $html;
        }

    }

}