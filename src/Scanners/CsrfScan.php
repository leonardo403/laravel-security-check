<?php

namespace Elohim\LaravelSecurityCheck\Scanners;

final class CsrfScan extends AbstractScanner implements ScannerInterface
{
    public static string $description = "CSRF Scanner";

    public function execute(): void
    {
        $this->info(self::CHECK . __("Verifying CSRF..."));

        if (!config('session.secure_cookie')) {
            $this->warn(self::WARNING . __("session.secure_cookie is disabled. Enable it for aditional security"));
        } else {
            $this->info(self::CHECK . __("session.secure_cookie is enabled"));
        }

        if (!config('session.same_site')) {
            $this->warn(self::WARNING . __("session.same_site not defined. Set it to `strict` or `lax`"));
        } else {
            $this->info(self::CHECK . __("session.same_site well configured: :same_site", ['same_site' => config('session.same_site')]));
        }
    }
}
