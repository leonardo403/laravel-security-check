<?php

namespace Elohim\LaravelSecurityCheck;

use Illuminate\Support\ServiceProvider;
use Elohim\LaravelSecurityCheck\Commands\SecurityScan;

class LaravelSecurityCheckServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SecurityScan::class,
            ]);
        }

        $this->publishes([
            dirname(__DIR__) . '/assets/config/security-check.php' => config_path('security-check.php'),
        ]);

        $this->loadJsonTranslationsFrom(dirname(__DIR__) . '/src/Lang');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/assets/config/security-check.php', 'security-check');
    }

}
