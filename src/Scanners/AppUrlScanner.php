<?php

namespace Elohim\LaravelSecurityCheck\Scanners;

use Illuminate\Support\Env;

final class AppUrlScanner extends AbstractScanner implements ScannerInterface
{
    public static string $description = "APP_URL Scanner";

    public function execute(): void
    {
        $this->info(self::CHECK . __("Verifying APP_URL..."));

        $appUrl = Env::get('APP_URL');
        if (!$appUrl) {
            $this->warn(self::WARNING . __("APP_URL is not defined. Define it on environment variables"));
        } elseif (!filter_var($appUrl, FILTER_VALIDATE_URL)) {
            $this->warn(self::WARNING . __("Invalid APP_URL: :url", ['url' => $appUrl]));
        } elseif (preg_match('/^(http?:\/\/localhost|http?:\/\/127\.0\.0\.1)/m', $appUrl)) {
            $this->warn(self::WARNING . __("APP_URL is set to localhost. Please provide the correct URL"));
        } else {
            $this->info(self::CHECK . __("APP_URL well defined: :url", ['url' => $appUrl]));
        }
    }
}
