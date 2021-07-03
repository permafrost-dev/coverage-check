<?php

namespace Permafrost\CoverageCheck\Commands;

use Permafrost\CoverageCheck\Configuration\Configuration;
use Permafrost\CoverageCheck\Configuration\ConfigurationFactory;
use Permafrost\CoverageCheck\CoverageChecker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCoverageCommand extends Command
{
    protected static $defaultName = 'check';

    /** @var OutputInterface */
    protected $output;

    /** @var InputInterface */
    protected $input;

    /** @var Configuration */
    protected $config;

    protected function configure(): void
    {
        $this->addArgument('filename')
            ->addOption('require', 'r', InputOption::VALUE_REQUIRED, 'Require a minimum code coverage percentage', null)
            ->addOption('coverage-only', 'C', InputOption::VALUE_NONE, 'Display only the code coverage percentage')
            ->setDescription('Checks a clover-format coverage file for a minimum coverage percentage and optionally enforces it.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $this->input = $input;
        $this->config = ConfigurationFactory::create($input);

        try {
            $this->config->validate();
        } catch(\InvalidArgumentException $e) {
            $output->writeln("<error>[ERROR]</error> {$e->getMessage()}");

            return Command::INVALID;
        }

        if ($this->config->requireMode) {
            [$result, $coverage] = $this->checkCoverage($this->config->filename, $this->config->required);
            $this->displayRequireModeResults($result, $coverage, $this->config->required);

            return $result ? Command::SUCCESS : Command::FAILURE;
        }

        $checker = new CoverageChecker($this->config->filename);
        $coverage = $checker->getCoveragePercent();

        $this->displayCoverageResults($coverage);

        return Command::SUCCESS;
    }

    protected function checkCoverage(string $filename, float $requiredPercentage): array
    {
        $checker = new CoverageChecker($filename);

        return [$checker->check($requiredPercentage), $checker->getCoveragePercent()];
    }

    protected function displayRequireModeResults(bool $result, float $coverage, float $percentage): void
    {
        $prefix = $result ? '<info>[PASS]</info>' : '<comment>[FAIL]</comment>';

        $this->output->writeln("$prefix Code coverage is {$coverage}% (required minimum is {$percentage}%).");
    }

    protected function displayCoverageResults(float $coverage): void
    {
        $message = $this->config->displayCoverageOnly
            ? $coverage
            : "Code coverage is {$coverage}%.";

        $this->output->writeln($message);
    }
}
