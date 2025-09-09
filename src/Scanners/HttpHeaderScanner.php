<?php

namespace LaravelSecurityCheck\Scanners;

use Illuminate\Support\Env;
use Illuminate\Support\Arr;

final class HttpHeaderScanner extends AbstractScanner implements ScannerInterface
{
    public static string $description = "HTTP header Scanner";

    public function execute(): void
    {
        $this->info(self::CHECK . __("Verifying HTTP headers..."));

        $url = Env::get('APP_URL', 'http://localhost');

        $headers = @get_headers($url, 1);

        $required = [
            'X-Frame-Options',
            'X-Content-Type-Options',
            'Strict-Transport-Security',
            'Referrer-Policy',
            'Content-Security-Policy',
        ];

        $clear = true;
        foreach ($required as $header) {
            if (!Arr::has($headers, mb_strtolower($header))) {
                $this->warn(self::WARNING . __("Missing header :header", ['header' => $header]));
                $clear = false;
            }
        }

        if ($clear) {
            $this->info(self::CHECK . __("HTTP headers are correct"));
        }
    }
}
