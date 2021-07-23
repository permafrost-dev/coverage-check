<?php

namespace Permafrost\CoverageCheck;

use Permafrost\CoverageCheck\Configuration\Configuration;
use SimpleXMLElement;

class CoverageChecker
{
    /** @var SimpleXMLElement */
    public $xml;

    /** @var Configuration */
    public $config;

    public function __construct(string $filename, Configuration $config)
    {
        $this->config = $config;
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

        [$totalName, $coveredName] = $this->getMetricFieldNames();

        $totalElements = $this->getMetricFieldSum($metrics, $totalName);
        $checkedElements = $this->getMetricFieldSum($metrics, $coveredName);

        return round(($checkedElements / $totalElements) * 100, $this->config->precision);
    }

    public function check(float $minPercentage): bool
    {
        return $this->getCoveragePercent() >= $minPercentage;
    }

    protected function getMetricFieldNames(): array
    {
        return ["{$this->config->metricField}s", "covered{$this->config->metricField}s"];
    }
}
