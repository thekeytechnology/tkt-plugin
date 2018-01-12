<?php

/**
 * @property string $optionId
 * @property string $optionName
 * @property string $optionType
 */
class OptionDefinition
{
    public $optionId;
    public $optionName;
    public $optionType;

    /**
     * OptionDefinition constructor.
     * @param string $optionId
     * @param string $optionName
     * @param string $optionType
     */
    public function __construct(string $optionId, string $optionName, string $optionType)
    {
        $this->optionId = $optionId;
        $this->optionName = $optionName;
        $this->optionType = $optionType;
    }
}
