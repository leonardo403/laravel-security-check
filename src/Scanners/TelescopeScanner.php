<?php

namespace LaravelSecurityCheck\Scanners;

use Illuminate\Support\Env;

final class TelescopeScanner extends AbstractScanner implements ScannerInterface
{
    public static string $description = "Telescope Scanner";

    public function execute(): void
    {
        $this->info(self::CHECK . __ ("Verifying Telescope..."));

        if (class_exists(\Laravel\Telescope\Telescope::class) || Env::get('TELESCOPE_ENABLED') === true) {
            $this->warn(self::WARNING . __("Telescope is enabled. Disable it in production"));
        } else {
            $this->info(self::CHECK . __("Telescope is disabled or not installed"));
        }
    }
}
