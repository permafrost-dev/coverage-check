<?php

namespace Permafrost\CoverageCheck\Configuration;

use Symfony\Component\Console\Input\InputInterface;

class ConfigurationFactory
{
    public static function create(InputInterface $input): Configuration
    {
        $filename = $input->getArgument('filename');
        $requireMode = $input->hasOption('require') && $input->getOption('require') !== null;
        $percentage = $requireMode ? (float)$input->getOption('require') : null;
        $displayCoverageOnly = $input->hasOption('coverage-only') && $input->getOption('coverage-only') === true;

        return new Configuration($filename, $requireMode, $percentage, $displayCoverageOnly);
    }
}
