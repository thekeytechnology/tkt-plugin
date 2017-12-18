<?php

/**
 * @property string $sectionId
 * @property string $sectionName
 * @property callable $callback
 */
class SectionDefinition
{
    public $sectionId;
    public $sectionName;
    public $callback;

    /**
     * OptionDefinition constructor.
     * @param string $sectionId
     * @param string $sectionName
     * @param callable callback
     */
    public function __construct(string $sectionId, string $sectionName, callable $callback = null)
    {
        $this->sectionId = $sectionId;
        $this->sectionName = $sectionName;
        $this->callback = $callback;
    }
}
