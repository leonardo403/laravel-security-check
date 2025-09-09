<?php

namespace LaravelSecurityCheck\Scanners;

use Illuminate\Support\Env;
use Illuminate\Support\Facades\Config;

final class QueueScanner extends AbstractScanner implements ScannerInterface
{
    public static string $description = "Queue Scanner";

    public function execute(): void
    {
        $this->info(self::CHECK . __("Verifying Queue..."));

        $connection = Env::get('QUEUE_CONNECTION', 'sync');
        $queueDriver = Config::get("queue.connections.$connection.driver");

        if (preg_match('/^(sync|null)/m', $queueDriver)) {
            $this->warn(self::WARNING . __("Queue driver not recommended for production: :driver", ['driver' => $queueDriver]));
        }
        if (preg_match('/^(redis|database|beanstalkd)/m', $queueDriver)) {
            $this->info(self::CHECK . __("Queue driver well configured: :driver", ['driver' => $queueDriver]));
        }
    }
}
