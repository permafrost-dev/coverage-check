<?php

namespace Permafrost\CoverageCheck\Tests;

use Permafrost\CoverageCheck\CoverageChecker;
use PHPUnit\Framework\TestCase;

class CoverageCheckerTest extends TestCase
{
    /** @test */
    public function it_gets_the_coverage_percentage()
    {
        $checker = new CoverageChecker(__DIR__ . '/data/coverage-clover.xml');

        $this->assertEquals(89.8765, round($checker->getCoveragePercent(), 4));
    }

    /** @test */
    public function it_checks_for_a_minimum_coverage_percentage()
    {
        $checker = new CoverageChecker(__DIR__ . '/data/coverage-clover.xml');

        $this->assertTrue($checker->check(75));
        $this->assertFalse($checker->check(99));
    }
}
