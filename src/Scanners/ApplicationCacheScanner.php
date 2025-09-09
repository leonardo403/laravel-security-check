<?php

namespace LaravelSecurityCheck\Scanners;

final class ApplicationCacheScanner extends AbstractScanner implements ScannerInterface
{
    public static string $description = "Application Cache Scanner";

    public function execute(): void
    {
        $this->info(self::CHECK . __("Verifying Application and Routes Cache..."));

        $clean = true;

        if (!file_exists(base_path('bootstrap/cache/config.php'))) {
            $this->warn(self::WARNING . __("Config cache not found. Execute: php artisan config:cache"));
            $clean = false;
        }
        if (!file_exists(base_path('bootstrap/cache/routes-v7.php')) && !file_exists(base_path('bootstrap/cache/routes.php'))) {
            $this->warn(self::WARNING . __("Route cache not found. Execute: php artisan route:cache"));
            $clean = false;
        }

        if ($clean) {
            $this->info(self::CHECK . __("Application and Routes Cache well configured"));
        }
    }
}
