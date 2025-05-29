<?php

namespace Elohim\LaravelSecurityCheck\Scanners;

use Illuminate\Support\Env;

final class MailerScanner extends AbstractScanner implements ScannerInterface
{
    public static string $description = "Mailer Scanner";

    public function execute(): void
    {
        $this->info(self::CHECK . __("Verifying Mailer..."));

        $mailDriver = Env::get('MAIL_MAILER', 'smtp');

        if (preg_match('/^(smtp|sendmail|mailgun)/m', $mailDriver)) {
            $this->info(self::CHECK . __("MAIL_MAILER well configured: :driver", ['driver' => $mailDriver]));
            return;
        }
        $this->warn(self::WARNING . __("MAIL_MAILER not recommended for production: :driver", ['driver' => $mailDriver]));
    }
}
