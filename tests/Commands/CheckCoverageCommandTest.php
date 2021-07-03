<?php

namespace Permafrost\CoverageCheck\Tests\Commands;

use Permafrost\CoverageCheck\Commands\CheckCoverageCommand;
use Permafrost\CoverageCheck\Tests\TestClasses\FakeOutput;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

class CheckCoverageCommandTest extends TestCase
{
    use MatchesSnapshots;

    /** @var CheckCoverageCommand */
    protected $command;

    /** @var FakeOutput */
    protected $output;

    protected function setUp(): void
    {
        parent::setUp();

        $this->command = new CheckCoverageCommand('check');
        $this->output = new FakeOutput();
    }

    protected function createInput(array $input)
    {
        $inputDefinition = new InputDefinition([
            new InputArgument('filename', InputArgument::REQUIRED),
            new InputOption('require', 'r', InputOption::VALUE_REQUIRED),
            new InputOption('coverage-only', 'C', InputOption::VALUE_NONE),
        ]);

        return new ArrayInput($input, $inputDefinition);
    }

    /** @test */
    public function it_executes_the_command_with_a_valid_filename_and_enforces_coverage()
    {
        $input = $this->createInput(['filename' => __DIR__ . '/../data/coverage-clover.xml', '--require' => 50]);

        $this->command->execute($input, $this->output);

        $this->assertMatchesSnapshot($this->output->writtenData);
    }

    /** @test */
    public function it_executes_the_command_with_an_invalid_filename_and_enforces_coverage()
    {
        $input = $this->createInput(['filename' => __DIR__ . '/../data/missing.xml', '--require' => 50]);

        $this->command->execute($input, $this->output);

        $this->assertMatchesSnapshot($this->output->writtenData);
    }

    /** @test */
    public function it_executes_the_command_with_a_valid_filename_and_does_not_enforce_coverage()
    {
        $input = $this->createInput(['filename' => __DIR__ . '/../data/coverage-clover.xml']);

        $this->command->execute($input, $this->output);

        $this->assertMatchesSnapshot($this->output->writtenData);
    }

    /** @test */
    public function it_executes_the_command_with_a_valid_filename_does_not_enforce_coverage_and_only_displays_coverage()
    {
        $input = $this->createInput(['filename' => __DIR__ . '/../data/coverage-clover.xml', '--coverage-only' => true]);

        $this->command->execute($input, $this->output);

        $this->assertMatchesSnapshot($this->output->writtenData);
    }
}
