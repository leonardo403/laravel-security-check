<?php

return [
    'scanners' => [
        \Elohim\LaravelSecurityCheck\Scanners\DependencyScanner::class,
        \Elohim\LaravelSecurityCheck\Scanners\EnvironmentScanner::class,
        \Elohim\LaravelSecurityCheck\Scanners\DebugbarScanner::class,
        \Elohim\LaravelSecurityCheck\Scanners\TelescopeScanner::class,
        \Elohim\LaravelSecurityCheck\Scanners\AppUrlScanner::class,
        \Elohim\LaravelSecurityCheck\Scanners\CacheScanner::class,
        \Elohim\LaravelSecurityCheck\Scanners\QueueScanner::class,
        \Elohim\LaravelSecurityCheck\Scanners\SessionScanner::class,
        \Elohim\LaravelSecurityCheck\Scanners\CsrfScan::class,
        \Elohim\LaravelSecurityCheck\Scanners\MailerScanner::class,
        \Elohim\LaravelSecurityCheck\Scanners\ApplicationCacheScanner::class,
        \Elohim\LaravelSecurityCheck\Scanners\FilesystemPermissionScanner::class,
        \Elohim\LaravelSecurityCheck\Scanners\HttpHeaderScanner::class
    ]
];
