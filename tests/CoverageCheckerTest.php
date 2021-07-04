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
        $config = new Configuration(__DIR__ . '/data/coverage-clover.xml', false, false, false, 'element');
        $checker = new CoverageChecker(__DIR__ . '/data/coverage-clover.xml', $config);

        $this->assertEquals(89.8765, round($checker->getCoveragePercent(), 4));
    }

    /** @test */
    public function it_checks_for_a_minimum_coverage_percentage()
    {
        $config = new Configuration(__DIR__ . '/data/coverage-clover.xml', false, false, false, 'element');
        $checker = new CoverageChecker(__DIR__ . '/data/coverage-clover.xml', $config);

        $this->assertTrue($checker->check(75));
        $this->assertFalse($checker->check(99));
    }

    /** @test */
    public function it_gets_the_coverage_percentage_for_the_statement_metric()
    {
        $config = new Configuration(__DIR__ . '/data/coverage-clover.xml', false, false, false, 'statement');
        $checker = new CoverageChecker(__DIR__ . '/data/coverage-clover.xml', $config);

        $this->assertEquals(90.6279, round($checker->getCoveragePercent(), 4));
    }

    /** @test */
    public function it_gets_the_coverage_percentage_for_the_method_metric()
    {
        $config = new Configuration(__DIR__ . '/data/coverage-clover.xml', false, false, false, 'method');
        $checker = new CoverageChecker(__DIR__ . '/data/coverage-clover.xml', $config);

        $this->assertEquals(87.4439, $checker->getCoveragePercent());
    }
}
