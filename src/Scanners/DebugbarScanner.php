<?php

namespace Elohim\LaravelSecurityCheck\Scanners;

use Illuminate\Support\Env;

final class DebugbarScanner extends AbstractScanner implements ScannerInterface
{
    public static string $description = "Debugbar Scanner";

    public function execute(): void
    {
        $this->info(self::CHECK . __("Verifying Debugbar..."));

        if (class_exists(\Debugbar::class) || Env::get('DEBUGBAR_ENABLED') === true) {
            $this->warn(self::WARNING . __("Debugbar is enabled. Disable it in production"));
        } else {
            $this->info(self::CHECK . __("Debugbar disabled or not installed"));
        }
    }
}
