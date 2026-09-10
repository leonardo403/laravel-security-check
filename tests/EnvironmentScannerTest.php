<?php

declare(strict_types=1);

namespace LaravelSecurityCheck\Tests;

use LaravelSecurityCheck\Scanners\EnvironmentScanner;
use Orchestra\Testbench\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Illuminate\Console\OutputStyle;

class EnvironmentScannerTest extends TestCase
{
    private const ENV_KEYS = [
        'APP_DEBUG',
        'APP_ENV',
        'APP_KEY',
        'APP_URL',
        'DB_CONNECTION',
        'DB_HOST',
        'DB_PORT',
        'DB_DATABASE',
        'DB_USERNAME',
        'DB_PASSWORD',
    ];

    private array $originalValues = [];

    private string $envPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->envPath = base_path('.env');

        $this->originalValues = [];
        foreach (self::ENV_KEYS as $key) {
            $this->originalValues[$key] = getenv($key) ?: null;
        }
    }

    protected function tearDown(): void
    {
        foreach (self::ENV_KEYS as $key) {
            $original = $this->originalValues[$key] ?? null;

            if ($original === null || $original === false) {
                putenv($key);
                unset($_ENV[$key]);
            } else {
                putenv("{$key}={$original}");
                $_ENV[$key] = $original;
            }
        }

        if (file_exists($this->envPath)) {
            unlink($this->envPath);
        }

        parent::tearDown();
    }

    public function test_warns_env_file_exists(): void
    {
        file_put_contents($this->envPath, '');

        $this->assertContainsString('A .env file was found', $this->runScanner());
    }

    public function test_no_warning_without_env_file(): void
    {
        if (file_exists($this->envPath)) {
            unlink($this->envPath);
        }

        $this->assertNotContainsString('A .env file was found', $this->runScanner());
    }

    public function test_warns_app_debug_true(): void
    {
        $this->setEnv('APP_DEBUG', 'true');

        $this->assertContainsString('APP_DEBUG=true', $this->runScanner());
    }

    public function test_no_warning_app_debug_false(): void
    {
        $this->setEnv('APP_DEBUG', 'false');

        $this->assertNotContainsString('APP_DEBUG=true', $this->runScanner());
    }

    public function test_warns_app_env_not_production(): void
    {
        $this->setEnv('APP_ENV', 'testing');

        $this->assertContainsString('APP_ENV=testing', $this->runScanner());
    }

    public function test_warns_app_key_empty(): void
    {
        $this->setEnv('APP_KEY', '');

        $this->assertContainsString('Invalid APP_KEY', $this->runScanner());
    }

    public function test_warns_app_url_localhost(): void
    {
        $this->setEnv('APP_URL', 'http://localhost');

        $this->assertContainsString('APP_URL=http://localhost', $this->runScanner());
    }

    public function test_warns_sqlite_memory_db(): void
    {
        $this->setEnv('DB_CONNECTION', 'sqlite');
        $this->setEnv('DB_DATABASE', ':memory:');

        $this->assertContainsString(
            'DB_CONNECTION=sqlite and DB_DATABASE=:memory:',
            $this->runScanner()
        );
    }

    public function test_warns_db_host_not_mysql(): void
    {
        $this->setEnv('DB_CONNECTION', 'mysql');
        $this->setEnv('DB_HOST', 'db');

        $this->assertContainsString("DB_HOST is not set to 'mysql'", $this->runScanner());
    }

    public function test_warns_db_port_empty(): void
    {
        $this->setEnv('DB_PORT', '');

        $this->assertContainsString('Invalid DB_PORT', $this->runScanner());
    }

    public function test_warns_db_port_invalid(): void
    {
        $this->setEnv('DB_PORT', 'abc');

        $this->assertContainsString('Invalid DB_PORT', $this->runScanner());
    }

    public function test_warns_db_database_empty(): void
    {
        $this->setEnv('DB_DATABASE', '');

        $this->assertContainsString('DB_DATABASE is not set', $this->runScanner());
    }

    public function test_warns_db_username_root(): void
    {
        $this->setEnv('DB_USERNAME', 'root');

        $this->assertContainsString('DB_USERNAME=root', $this->runScanner());
    }

    public function test_warns_db_password_weak(): void
    {
        $this->setEnv('DB_PASSWORD', 'root');

        $this->assertContainsString('DB_PASSWORD is empty or too weak', $this->runScanner());
    }

    public function test_no_warning_db_password_strong(): void
    {
        $this->setEnv('DB_PASSWORD', 'Kj#9x!mL2@vQ');

        $this->assertNotContainsString('DB_PASSWORD is empty or too weak', $this->runScanner());
    }

    private function runScanner(): string
    {
        $buffered = new BufferedOutput();
        $output = new OutputStyle(new ArrayInput([]), $buffered);

        $scanner = new EnvironmentScanner();
        $scanner->setOutput($output);
        $scanner->execute();

        return (string) $buffered->fetch();
    }

    private function setEnv(string $key, string $value): void
    {
        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
    }

    private function assertContainsString(string $needle, string $haystack): void
    {
        $this->assertStringContainsString($needle, $haystack);
    }

    private function assertNotContainsString(string $needle, string $haystack): void
    {
        $this->assertStringNotContainsString($needle, $haystack);
    }
}
