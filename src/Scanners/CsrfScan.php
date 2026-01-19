<?php

namespace LaravelSecurityCheck\Scanners;

final class CsrfScan extends AbstractScanner implements ScannerInterface
{
    public static string $description = "CSRF Scanner";

    public function execute(): void
    {
        $this->info(self::CHECK . __("Verifying CSRF..."));

        $this->reportSetting(
            config('session.secure'),
            'session.secure is enabled',
            'session.secure is disabled. Enable it for aditional security'
        );

        $this->reportSetting(
            config('session.same_site'),
            'session.same_site well configured: :same_site',
            'session.same_site not defined. Set it to `strict` or `lax`',
            ['same_site' => config('session.same_site')]
        );
    }
}
