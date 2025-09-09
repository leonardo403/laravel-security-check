<?php

namespace LaravelSecurityCheck\Scanners;

final class FilesystemPermissionScanner extends AbstractScanner implements ScannerInterface
{
    public static string $description = "Filesystem Permission Scanner";

    public function execute(): void
    {
        $this->info(self::CHECK . __("Verifying permissions on files and folders..."));

        $clear = true;
        foreach (['storage', 'bootstrap/cache'] as $dir) {
            $path = base_path($dir);
            $perms = substr(sprintf('%o', fileperms($path)), -3);
            if ($perms !== '755') {
                $this->warn(
                    self::WARNING
                    . __("Incorrect permisions in :folder (current: :cur_perms, expected: 755)", [
                        'folder' => $dir,
                        'cur_perms' => $perms,
                    ])
                );
                $clear = false;
            }
        }
        if ($clear) {
            $this->info(self::CHECK . __("Filesystem permissions are correct"));
        }
    }
}
