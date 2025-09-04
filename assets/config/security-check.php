<?php

return [
    'scanners' => [
        \LaravelSecurityCheck\Scanners\DependencyScanner::class,
        \LaravelSecurityCheck\Scanners\EnvironmentScanner::class,
        \LaravelSecurityCheck\Scanners\DebugbarScanner::class,
        \LaravelSecurityCheck\Scanners\TelescopeScanner::class,
        \LaravelSecurityCheck\Scanners\AppUrlScanner::class,
        \LaravelSecurityCheck\Scanners\CacheScanner::class,
        \LaravelSecurityCheck\Scanners\QueueScanner::class,
        \LaravelSecurityCheck\Scanners\SessionScanner::class,
        \LaravelSecurityCheck\Scanners\CsrfScan::class,
        \LaravelSecurityCheck\Scanners\MailerScanner::class,
        \LaravelSecurityCheck\Scanners\ApplicationCacheScanner::class,
        \LaravelSecurityCheck\Scanners\FilesystemPermissionScanner::class,
        \LaravelSecurityCheck\Scanners\HttpHeaderScanner::class
    ]
];
