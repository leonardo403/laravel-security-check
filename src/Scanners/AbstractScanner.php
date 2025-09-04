<?php

namespace LaravelSecurityCheck\Scanners;

use Illuminate\Console\Concerns\InteractsWithIO;
use Symfony\Component\Console\Output\ConsoleOutput;

abstract class AbstractScanner
{
    protected const CHECK = "✅";
    protected const WARNING = "⚠️";

    use InteractsWithIO;

    public static string $description = "Undefined Scanner Description";

    public function __construct()
    {
        $this->output = $this->output ?? new ConsoleOutput();
    }
}
