<?php

namespace Permafrost\CoverageCheck\Commands;

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

    protected function configure(): void
    {
        $this->addArgument('filename')
            ->addOption('require', 'r', InputOption::VALUE_REQUIRED, 'Require a minimum code coverage percentage', null)
            ->setDescription('Checks a clover-format coverage file for a minimum coverage percentage and optionally enforces it.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;

        $inputFile = $input->getArgument('filename');
        $requireMode = $input->hasOption('require') && $input->getOption('require') !== null;
        $percentage = (float)($input->getOption('require') ?? 0.0);
        $percentage = min(100, max(0, $percentage));

        try {
            $this->verifyInputArguments($inputFile, $percentage, $requireMode);
        } catch(\InvalidArgumentException $e) {
            $output->writeln("<error>[ERROR]</error> {$e->getMessage()}");

            return Command::INVALID;
        }

        [$result, $coverage] = $this->checkCoverage($inputFile, $percentage);

        if ($requireMode) {
            $this->displayRequireModeResults($result, $coverage, $percentage);
            return $result ? Command::SUCCESS : Command::FAILURE;
        }

        $this->displayCoverageResults($coverage);

        return Command::SUCCESS;
    }

    protected function verifyInputArguments(string $filename, $percentage, bool $requireMode): void
    {
        if (! file_exists($filename) || ! is_file($filename)) {
            throw new \InvalidArgumentException('Invalid input file provided.');
        }

        if (! $percentage && $requireMode) {
            throw new \InvalidArgumentException('A required percentage value must be given as the second parameter.');
        }
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
        $this->output->writeln("Code coverage is {$coverage}%.");
    }
}
