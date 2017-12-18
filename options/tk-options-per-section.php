<?php

/**
 * @property SectionDefinition $sectionDefinition
 * @property OptionDefinition[] $options
 */
class OptionsPerSection
{
    public $sectionDefinition;
    public $options;

    /**
     * OptionsPerSection constructor.
     * @param SectionDefinition $sectionDefinition
     * @param OptionDefinition[] $options
     */
    public function __construct(SectionDefinition $sectionDefinition, array $options)
    {
        $this->sectionDefinition = $sectionDefinition;
        $this->options = $options;
    }
}
