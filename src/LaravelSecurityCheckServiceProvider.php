<?php

namespace Elohim\LaravelSecurityCheck;

use Illuminate\Support\ServiceProvider;
use Elohim\LaravelSecurityCheck\Commands\SecurityScanCommand;

class LaravelSecurityCheckServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SecurityScanCommand::class,
            ]);
        }
    }
}
