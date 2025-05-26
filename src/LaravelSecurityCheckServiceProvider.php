<?php

namespace Elohim\LaravelSecurityCheck;

use Illuminate\Support\ServiceProvider;
use Elohim\LaravelSecurityCheck\Commands\ConfigSecurityScan;
use PSpell\Config;

class LaravelSecurityCheckServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ConfigSecurityScan::class,
            ]);
        }
    }
}
