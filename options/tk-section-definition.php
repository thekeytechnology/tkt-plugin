<?php

/**
 * @property string $sectionId
 * @property string $sectionName
 * @property string capability
 * @property callable $callback
 */
class SectionDefinition
{
    public $sectionId;
    public $sectionName;
    public $callback;
    public $capability;

    /**
     * OptionDefinition constructor.
     * @param string $sectionId
     * @param string $sectionName
     * @param string $capability
     * @param callable callback
     */
    public function __construct(string $sectionId, string $sectionName, string $capability = null, callable $callback = null)
    {
        $this->sectionId = $sectionId;
        $this->sectionName = $sectionName;
        $this->capability = $capability;
        $this->callback = $callback;
    }
}
