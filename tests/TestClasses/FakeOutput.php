<?php

namespace Permafrost\CoverageCheck\Tests\TestClasses;

use Symfony\Component\Console\Output\Output;

class FakeOutput extends Output
{
    /** @var array|string[] */
    public $writtenData = [];

    public function write($messages, bool $newline = false, int $options = 0)
    {
        $this->writtenData[] = $messages . ($newline ? PHP_EOL : '');
    }

    public function writeln($messages, int $options = 0)
    {
        $this->writtenData[] = $messages;
    }

    protected function doWrite(string $message, bool $newline)
    {
        //
    }
}
