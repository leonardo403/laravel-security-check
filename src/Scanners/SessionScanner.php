<?php

namespace LaravelSecurityCheck\Scanners;

use Illuminate\Support\Env;

final class SessionScanner extends AbstractScanner implements ScannerInterface
{
    public static string $description = "Session Scanner";

    public function execute(): void
    {
        $this->info(self::CHECK . __("Verifying Session..."));

        $sessionDriver = Env::get('SESSION_DRIVER', 'file');

        if (preg_match('/^(file|array)/m', $sessionDriver)) {
            $this->warn(self::WARNING . __("SESSION_DRIVER not recommended for production: :driver", ['driver' => $sessionDriver]));
        }
        if (preg_match('/^(redis|database|cookie)/m', $sessionDriver)) {
            $this->info(self::CHECK . __("SESSION_DRIVER well configured: :driver", ['driver' => $sessionDriver]));
        }
    }
}
