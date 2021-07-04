<?php

namespace Permafrost\CoverageCheck\Tests\Configuration;

use Permafrost\CoverageCheck\Configuration\ConfigurationFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

class ConfigurationFactoryTest extends TestCase
{
    protected function createInput(array $input)
    {
        $inputDefinition = new InputDefinition([
            new InputArgument('filename', InputArgument::REQUIRED),
            new InputOption('require', 'r', InputOption::VALUE_REQUIRED),
            new InputOption('metric', 'm', InputOption::VALUE_REQUIRED),
            new InputOption('coverage-only', 'C', InputOption::VALUE_NONE),
        ]);

        return new ArrayInput($input, $inputDefinition);
    }

    /** @test */
    public function it_creates_a_configuration_object()
    {
        $filename = realpath(__DIR__.'/../data/coverage-clover.xml');

        $input = $this->createInput(['filename' => $filename, '--require' => 50, '--coverage-only' => true]);

        $config = ConfigurationFactory::create($input);

        $this->assertTrue($config->requireMode);
        $this->assertEquals(50, $config->required);
        $this->assertEquals($filename, $config->filename);
        $this->assertTrue($config->displayCoverageOnly);
    }

    /** @test */
    public function it_throws_an_exception_when_validating_the_configuration_if_the_filename_does_not_exist()
    {
        $filename = realpath(__DIR__.'/../data/missing.xml');

        $input = $this->createInput(['filename' => $filename, '--require' => 50]);
        $config = ConfigurationFactory::create($input);

        $this->expectException(\InvalidArgumentException::class);

        $config->validate();
    }

    /** @test */
    public function it_does_not_throw_an_exception_when_validating_the_configuration_if_the_filename_exists()
    {
        $filename = realpath(__DIR__.'/../data/coverage-clover.xml');

        $input = $this->createInput(['filename' => $filename, '--require' => 50]);
        $config = ConfigurationFactory::create($input);
        $hasException = false;

        try {
            $config->validate();
        } catch (\Exception $e) {
            $hasException = true;
        }

        $this->assertFalse($hasException);
    }

    /** @test */
    public function it_throws_an_exception_when_validating_the_metric_field_with_an_invalid_value()
    {
        $filename = realpath(__DIR__.'/../data/coverage-clover.xml');

        $input = $this->createInput(['filename' => $filename, '--metric' => 'bad']);
        $config = ConfigurationFactory::create($input);

        $this->expectException(\InvalidArgumentException::class);

        $config->validate();
    }
}
