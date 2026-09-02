<?php

namespace LaravelSecurityCheck\Scanners;

use Illuminate\Support\Env;

final class EnvironmentScanner extends AbstractScanner implements ScannerInterface
{

    private const WEAK_PASSWORDS = [
        'root',
        'password',
        'password123',
        '123456',
        '12345678',
        'secret',
        'admin',
        'qwerty',
    ];

    public static string $description = "Environment Scanner";

    public function execute(): void
    {
        $this->info(self::CHECK. __("Verifying .env"));

        $env = base_path('.env');
        $dbPort = Env::get('DB_PORT');
        $dbUsername = Env::get('DB_USERNAME');
        $dbPassword = Env::get('DB_PASSWORD');

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

        if (Env::get('APP_URL') === 'http://localhost') {
            $this->warn(self::WARNING . __("APP_URL=http://localhost -> Use your production URL in production environments."));
        }

        if (Env::get('DB_CONNECTION') === 'sqlite' && Env::get('DB_DATABASE') === ':memory:') {
            $this->warn(self::WARNING . __("DB_CONNECTION=sqlite and DB_DATABASE=:memory: -> Use a persistent database in production environments."));
        }
        
        if (Env::get('DB_HOST') != 'mysql' && Env::get('DB_CONNECTION') === 'mysql') {
            $this->warn(self::WARNING . __("DB_HOST is not set to 'mysql' -> Use the correct database host in production environments."));
        }
        
        if (empty($dbPort) || !is_numeric($dbPort) || (int) $dbPort < 1 || (int) $dbPort > 65535) {
            $this->warn(self::WARNING . __("Invalid DB_PORT -> Use a valid database port in production environments."));
        }

        if (empty(Env::get('DB_DATABASE'))) {
            $this->warn(self::WARNING . __("DB_DATABASE is not set -> Define the database name in production environments."));
        }

        if (empty($dbUsername) || $dbUsername === 'root') {
            $this->warn(self::WARNING . __("DB_USERNAME=:current_user -> Avoid using 'root' in production environments.", ['current_user' => $dbUsername]));
        }

        if (empty($dbPassword) || in_array($dbPassword, self::WEAK_PASSWORDS, true)) {
            $this->warn(self::WARNING . __("DB_PASSWORD is empty or too weak -> Use a strong database password in production environments."));
        }

    }
}