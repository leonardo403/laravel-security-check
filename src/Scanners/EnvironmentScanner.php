<?php

namespace Elohim\LaravelSecurityCheck\Scanners;

use Illuminate\Support\Env;

final class EnvironmentScanner extends AbstractScanner implements ScannerInterface
{

    public static string $description = "Environment Scanner";

    public function execute(): void
    {
        $this->info(self::CHECK. __("Verifying .env"));

        $env = base_path('.env');

        if (file_exists($env)) {
            $this->warn(self::WARNING . __("A .env file was found, please use enviroment variables instead"));
        }

        if (Env::get('APP_DEBUG') === true) {
            $this->warn(self::WARNING . __("APP_DEBUG=true -> Define as false in production environment."));
        }

        if (Env::get('APP_ENV') !== 'production') {
            $this->warn(self::WARNING . __("APP_ENV=:current_env -> Use `production` in production environments", ['current_env' => Env::get('APP_ENV')]));
        }

        if (empty(Env::get('APP_KEY'))) {
            $this->warn(self::WARNING . __("Invalid APP_KEY. Execute php artisan key:generate."));
        }
    }
}
