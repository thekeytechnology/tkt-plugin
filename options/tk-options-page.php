<?php

/**
 * @property OptionsConfiguration $optionsConfiguration
 */
class TkOptionsPage
{
    private $optionsConfiguration;

    public function init(OptionsConfiguration $optionsConfiguration)
    {
        $this->optionsConfiguration = $optionsConfiguration;
        foreach ($optionsConfiguration->optionDefinitions as $optionDefinition) {
            register_setting($optionsConfiguration->optionsGroupName, $optionDefinition->optionId, array(
                "sanitize_callback" => "sanitize_text_field"
            ));
        }

        add_settings_section(
            'general',
            'General',
            "",
            $optionsConfiguration->pageSlug
        );

        foreach ($optionsConfiguration->optionDefinitions as $optionDefinition) {
            add_settings_field(
                $optionDefinition->optionId,
                $optionDefinition->optionName,
                function () use ($optionDefinition) {
                    $option = get_option($optionDefinition->optionId);
                    printf(
                        '<input type="text" id="%s" name="%s" value="%s" />',
                        $optionDefinition->optionId,
                        $optionDefinition->optionId,
                        isset($option) ? esc_attr($option) : ''
                    );
                },
                $optionsConfiguration->pageSlug,
                'general'
            );
        }
    }


    function display()
    {
        ?>
        <h1>Options</h1>
        <div class="wrap">
            <form action="options.php" method="post">
                <?php
                settings_fields($this->optionsConfiguration->optionsGroupName);
                do_settings_sections($this->optionsConfiguration->pageSlug);
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

}


function tkAddOptionsPage(OptionsConfiguration $optionsConfiguration)
{
    add_action('admin_menu', function () use ($optionsConfiguration) {
        add_submenu_page(
            $optionsConfiguration->parentSlug,
            $optionsConfiguration->pageTitle,
            $optionsConfiguration->menuTitle,
            'manage_options',
            $optionsConfiguration->pageSlug,
            function () {
                global $tkOptionsPage;
                $tkOptionsPage->display();
            }
        );
    });

    add_action("admin_init", function () use ($optionsConfiguration) {
        global $tkOptionsPage;
        $tkOptionsPage = new TkOptionsPage();
        $tkOptionsPage->init($optionsConfiguration);
    });
}