<?php

namespace Elohim\LaravelSecurityCheck\Scanners;

use Illuminate\Console\Concerns\InteractsWithIO;

abstract class AbstractScanner
{
    protected const string CHECK = "✅";
    protected const string WARNING = "⚠️";

    use InteractsWithIO;

    public static string $description = "Undefined Scanner Description";

    public function __construct()
    {
        $this->output = $this->output ?? new \Symfony\Component\Console\Output\ConsoleOutput();
    }
}
