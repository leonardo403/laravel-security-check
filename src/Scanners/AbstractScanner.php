<?php

namespace LaravelSecurityCheck\Scanners;

use Illuminate\Console\Concerns\InteractsWithIO;
use Symfony\Component\Console\Output\ConsoleOutput;
use LaravelSecurityCheck\Traits\ReportSettingTrait;

abstract class AbstractScanner
{
    use ReportSettingTrait;
    protected const CHECK = "✅";
    protected const WARNING = "⚠️";

    use InteractsWithIO;

    public static string $description = "Undefined Scanner Description";

    public function __construct()
    {
        $this->output = $this->output ?? new ConsoleOutput();
    }
}
