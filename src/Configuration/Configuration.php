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

    public function __construct(string $filename, bool $requireMode, $required, bool $displayCoverageOnly)
    {
        $this->filename = $filename;
        $this->required = $required;
        $this->requireMode = $requireMode;
        $this->displayCoverageOnly = $displayCoverageOnly;
    }

    public function validate(): void
    {
        if (! file_exists($this->filename) || ! is_file($this->filename)) {
            throw new \InvalidArgumentException('Invalid input file provided.');
        }
    }
}
