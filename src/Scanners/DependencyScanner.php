<?php

namespace Elohim\LaravelSecurityCheck\Scanners;

use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Arr;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

final class DependencyScanner extends AbstractScanner implements ScannerInterface
{
    public static string $description = "Dependency Scanner";

    public function execute(): void
    {
        $this->info(self::CHECK . __('Verifying outdated dependencies'));
        $result = Process::run('composer outdated --direct --format=json');
        $data = json_decode($result->output(), true);

        if (!Arr::get($data, 'installed')) {
            $this->info(self::CHECK . __("All dependencies are up to date"));
        } else {
            $this->warn(self::WARNING . __("Outdated dependencies found:"));
            foreach (Arr::get($data, 'installed') as $package) {
                $this->warn(
                    sprintf(
                        "%s: [%s] -> [%s]",
                        Arr::get($package, 'name'),
                        Arr::get($package, 'version'),
                        Arr::get($package, 'latest')
                    )
                );
            }
        }
    }
}
