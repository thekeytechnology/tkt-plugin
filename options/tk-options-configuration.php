<?php

/**
 * @property string $optionsGroupName
 * @property string $parentSlug
 * @property string $pageTitle
 * @property string $menuTitle
 * @property string $pageSlug
 * @property string capability
 * @property OptionsPerSection[] $optionsPerSection
 */
class OptionsConfiguration
{
    public $optionsGroupName;
    public $parentSlug;
    public $pageTitle;
    public $menuTitle;
    public $pageSlug;
    public $capability;
    public $optionsPerSection;

    /**
     * OptionsConfiguration constructor.
     * @param string $optionsGroupName
     * @param string $parentSlug
     * @param string $pageTitle
     * @param string $menuTitle
     * @param string $pageSlug
     * @param string $capability
     * @param OptionsPerSection[] $optionsPerSection
     */
    public function __construct(string $optionsGroupName, string $parentSlug, string $pageTitle, string $menuTitle, string $pageSlug, string $capability, array $optionsPerSection)
    {
        $this->capability = $capability;
        $this->optionsGroupName = $optionsGroupName;
        $this->parentSlug = $parentSlug;
        $this->pageTitle = $pageTitle;
        $this->menuTitle = $menuTitle;
        $this->pageSlug = $pageSlug;
        $this->optionsPerSection = $optionsPerSection;
    }

}