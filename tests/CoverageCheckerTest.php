<?php

namespace Permafrost\CoverageCheck\Tests;

use Permafrost\CoverageCheck\Configuration\Configuration;
use Permafrost\CoverageCheck\CoverageChecker;
use PHPUnit\Framework\TestCase;

class CoverageCheckerTest extends TestCase
{
    /** @test */
    public function it_gets_the_coverage_percentage()
    {
        $config = new Configuration(__DIR__ . '/data/coverage-clover.xml', false, false, false, 'element', 4);
        $checker = new CoverageChecker(__DIR__ . '/data/coverage-clover.xml', $config);

        $this->assertEquals(89.8765, round($checker->getCoveragePercent(), 4));
    }

    /** @test */
    public function it_gets_the_coverage_percentage_using_the_specified_precision()
    {
        $map = [
            0 => 90.0,
            1 => 89.9,
            2 => 89.88,
        ];

        foreach ($map as $precision => $value) {
            $config = new Configuration(__DIR__ . '/data/coverage-clover.xml', false, false, false, 'element', $precision);
            $checker = new CoverageChecker(__DIR__ . '/data/coverage-clover.xml', $config);

            $this->assertEquals($value, $checker->getCoveragePercent());
        }
    }

    /** @test */
    public function it_checks_for_a_minimum_coverage_percentage()
    {
        $config = new Configuration(__DIR__ . '/data/coverage-clover.xml', false, false, false, 'element', 4);
        $checker = new CoverageChecker(__DIR__ . '/data/coverage-clover.xml', $config);

        $this->assertTrue($checker->check(75));
        $this->assertFalse($checker->check(99));
    }

    /** @test */
    public function it_gets_the_coverage_percentage_for_the_statement_metric()
    {
        $config = new Configuration(__DIR__ . '/data/coverage-clover.xml', false, false, false, 'statement', 4);
        $checker = new CoverageChecker(__DIR__ . '/data/coverage-clover.xml', $config);

        $this->assertEquals(90.6279, round($checker->getCoveragePercent(), 4));
    }

    /** @test */
    public function it_gets_the_coverage_percentage_for_the_method_metric()
    {
        $config = new Configuration(__DIR__ . '/data/coverage-clover.xml', false, false, false, 'method', 4);
        $checker = new CoverageChecker(__DIR__ . '/data/coverage-clover.xml', $config);

        $this->assertEquals(87.4439, $checker->getCoveragePercent());
    }
}
