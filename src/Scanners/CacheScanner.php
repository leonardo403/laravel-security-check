<?php

namespace LaravelSecurityCheck\Scanners;

use Illuminate\Support\Env;

final class CacheScanner extends AbstractScanner implements ScannerInterface
{
    public static string $description = "Cache Scanner";

    public function execute(): void
    {
        $this->info(self::CHECK . __("Verifying Cache..."));

        $cacheDriver = Env::get('CACHE_DRIVER', 'file');

        if (empty($cacheDriver)) {
            $this->warn(self::WARNING . __("CACHE_DRIVER not defined"));
        }

        if (preg_match('/^(file|array)/m', $cacheDriver)) {
            $this->warn(self::WARNING . __("CACHE_DRIVER `:driver` not recommended for production. Try `memcached` or `redis`", ['driver' => $cacheDriver]));
        }
        if (preg_match('/^(redis|memcached|database)/m', $cacheDriver)) {
            $this->info(self::CHECK . __("CACHE_DRIVER well configured: :driver", ['driver' => $cacheDriver]));
        }
    }
}
