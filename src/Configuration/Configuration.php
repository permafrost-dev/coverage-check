<?php

namespace Permafrost\CoverageCheck\Configuration;

class Configuration
{
    /** @var string */
    public $filename;

    /** @var int|float|null */
    public $required = null;

    /** @var bool */
    public $requireMode = false;

    /** @var bool */
    public $displayCoverageOnly = false;

    /** @var string */
    public $metricField;

    public function __construct(string $filename, bool $requireMode, $required, bool $displayCoverageOnly, string $metricField)
    {
        $this->filename = $filename;
        $this->required = $required;
        $this->requireMode = $requireMode;
        $this->displayCoverageOnly = $displayCoverageOnly;
        $this->metricField = rtrim(strtolower($metricField), 's');
    }

    public function validate(): void
    {
        if (! file_exists($this->filename) || ! is_file($this->filename)) {
            throw new \InvalidArgumentException('Invalid input file provided.');
        }

        if (! in_array($this->metricField, ['element', 'statement', 'method'])) {
            throw new \InvalidArgumentException('Invalid metric field name provided. Valid options are "element", "statement", "method".');
        }
    }
}
