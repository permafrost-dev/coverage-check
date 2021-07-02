<?php

namespace Permafrost\CoverageCheck\Commands;

use Permafrost\CoverageCheck\CoverageChecker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCoverageCommand extends Command
{
    protected static $defaultName = 'check';

    /** @var OutputInterface */
    protected $output;

    protected function configure(): void
    {
        $this->addArgument('filename')
            ->addArgument('percentage')
            ->setDescription('Checks a clover-format coverage file for a minimum coverage percentage');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;

        $inputFile = $input->getArgument('filename');
        $percentage = (float)$input->getArgument('percentage');
        $percentage = min(100, max(0, $percentage));

        try {
            $this->verifyInputArguments($inputFile, $percentage);
        } catch(\InvalidArgumentException $e) {
            $output->writeln("<error>[ERROR]</error> {$e->getMessage()}");

            return Command::INVALID;
        }

        [$result, $coverage] = $this->checkCoverage($inputFile);

        $this->displayResults($result, $coverage, $percentage);

        return $result ? Command::SUCCESS : Command::FAILURE;
    }

    protected function verifyInputArguments(string $filename, $percentage): void
    {
        if (! file_exists($filename) || ! is_file($filename)) {
            throw new \InvalidArgumentException('Invalid input file provided.');
        }

        if (! $percentage) {
            throw new \InvalidArgumentException('A required percentage value must be given as the second parameter.');
        }
    }

    protected function checkCoverage(string $filename): array
    {
        $checker = new CoverageChecker($filename);

        return [$checker->check($filename), $checker->getCoveragePercent()];
    }

    protected function displayResults(bool $result, float $coverage, float $percentage): void
    {
        $prefix = $result ? '<info>[PASS]</info>' : '<comment>[FAIL]</comment>';

        $this->output->writeln("$prefix Code coverage is {$coverage}% (required minimum is {$percentage}%).");
    }

}
