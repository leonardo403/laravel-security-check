<?php

namespace LaravelSecurityCheck\Commands;

use LaravelSecurityCheck\Scanners\ScannerInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class SecurityScan extends Command
{
    protected $signature = 'security:scan';

    protected $description = 'Validates configuration and best practices on Laravel based projects';

    public function __construct() {
        $this->description = __($this->description);
        parent::__construct();
    }

    public function handle(): void
    {
        foreach (Config::get('security-check.scanners') as $scanner) {
            if (!class_exists($scanner)) {
                $this->error(__("Scanner :scanner not found", ['scanner' => $scanner]));
                continue;
            }
            $scannerInstance = App::make($scanner);
            if ($scannerInstance instanceof ScannerInterface) {
                $this->info(__("== Running :scanner ==", ['scanner' => __($scanner::$description)]));
                $scannerInstance->execute();
                continue;
            }
            $this->error(__("Class :scanner don't implements ScannerInterface", ['scanner' => $scanner]));
        }
    }
}
