<?php

namespace Permafrost\CoverageCheck;

use SimpleXMLElement;

class CoverageChecker
{
    /** @var SimpleXMLElement */
    public $xml;

    public function __construct(string $filename)
    {
        $this->xml = new SimpleXMLElement(file_get_contents($filename));
    }

    protected function getMetricFieldSum(array $metrics, string $propertyName): int
    {
        $result = 0;

        foreach ($metrics as $metric) {
            $result += (int)$metric[$propertyName];
        }

        return $result;
    }

    public function getCoveragePercent(): float
    {
        $metrics = $this->xml->xpath('//metrics');

        $totalElements = $this->getMetricFieldSum($metrics, 'elements');
        $checkedElements = $this->getMetricFieldSum($metrics, 'coveredelements');

        return round(($checkedElements / $totalElements) * 100, 4);
    }

    public function check(float $minPercentage): bool
    {
        return $this->getCoveragePercent() >= $minPercentage;
    }
}
