<?php
require_once("tk-option-definition.php");
require_once("tk-section-definition.php");
require_once("tk-options-per-section.php");
require_once("tk-options-configuration.php");

/**
 * @property OptionsConfiguration $optionsConfiguration
 */
class TkOptionsPage
{
    private $optionsConfiguration;

    public function init(OptionsConfiguration $optionsConfiguration)
    {
        $this->optionsConfiguration = $optionsConfiguration;


        foreach ($optionsConfiguration->optionsPerSection as $optionsPerSection) {
            add_settings_section(
                $optionsPerSection->sectionDefinition->sectionId,
                $optionsPerSection->sectionDefinition->sectionName,
                $optionsPerSection->sectionDefinition->callback,
                $optionsConfiguration->pageSlug
            );

            foreach ($optionsPerSection->options as $optionDefinition) {
                $sanitizeCallback = null;
                switch ($optionDefinition->optionType) {
                    case "text":
                    case "textarea":
                        $sanitizeCallback = "sanitize_text_field";
                        break;
                    case "integer":
                        $sanitizeCallback = "intval";
                        break;
                }

                register_setting($optionsConfiguration->optionsGroupName, $optionDefinition->optionId, array(
                    "sanitize_callback" => $sanitizeCallback
                ));

                add_settings_field(
                    $optionDefinition->optionId,
                    $optionDefinition->optionName,
                    function () use ($optionDefinition) {
                        $option = get_option($optionDefinition->optionId);

                        switch ($optionDefinition->optionType) {
                            case "text":
                                printf(
                                    '<input type="text" id="%s" name="%s" value="%s" />',
                                    $optionDefinition->optionId,
                                    $optionDefinition->optionId,
                                    isset($option) ? esc_attr($option) : ''
                                );
                                break;
                            case "textarea":
                                printf(
                                    '<textarea id="%s" name="%s">%s</textarea>',
                                    $optionDefinition->optionId,
                                    $optionDefinition->optionId,
                                    isset($option) ? esc_attr($option) : ''
                                );
                                break;
                            case "integer":
                                printf(
                                    '<input type="number" step="1" id="%s" name="%s" value="%s" />',
                                    $optionDefinition->optionId,
                                    $optionDefinition->optionId,
                                    isset($option) ? esc_attr($option) : ''
                                );
                                break;
                        }
                    },
                    $optionsConfiguration->pageSlug,
                    $optionsPerSection->sectionDefinition->sectionId
                );
            }
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