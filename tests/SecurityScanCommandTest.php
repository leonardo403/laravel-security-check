<?php

declare(strict_types=1);

namespace LaravelSecurityCheck\Tests;

use Orchestra\Testbench\TestCase;
use LaravelSecurityCheck\LaravelSecurityCheckServiceProvider;

class SecurityScanCommandTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
           LaravelSecurityCheckServiceProvider::class,
        ];
    }

    public function test_security_scan_command_runs()
    {
        $this->artisan('security:scan')
            ->assertExitCode(0)
            ->expectsOutputToContain('== Running');
    }
}