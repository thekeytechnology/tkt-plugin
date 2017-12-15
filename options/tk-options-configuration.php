<?php

/**
 * @property string $optionsGroupName
 * @property string $parentSlug
 * @property string $pageTitle
 * @property string $menuTitle
 * @property string $pageSlug
 * @property OptionDefinition[] $optionDefinitions
 */
class OptionsConfiguration
{
    public $optionsGroupName;
    public $parentSlug;
    public $pageTitle;
    public $menuTitle;
    public $pageSlug;
    public $optionDefinitions;

    /**
     * OptionsConfiguration constructor.
     * @param string $optionsGroupName
     * @param string $parentSlug
     * @param string $pageTitle
     * @param string $menuTitle
     * @param string $pageSlug
     * @param OptionDefinition[] $optionDefinitions
     */
    public function __construct(string $optionsGroupName, string $parentSlug, string $pageTitle, string $menuTitle, string $pageSlug, array $optionDefinitions)
    {
        $this->optionsGroupName = $optionsGroupName;
        $this->parentSlug = $parentSlug;
        $this->pageTitle = $pageTitle;
        $this->menuTitle = $menuTitle;
        $this->pageSlug = $pageSlug;
        $this->optionDefinitions = $optionDefinitions;
    }

}