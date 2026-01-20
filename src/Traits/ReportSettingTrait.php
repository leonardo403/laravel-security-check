<?php

namespace LaravelSecurityCheck\Traits;

trait ReportSettingTrait
{
    protected function reportSetting(mixed $value, string $okMessage, string $warnMessage, array $replacements = []): void
    {
        $method = $value ? 'info' : 'warn';
        $message = $value
            ? self::CHECK . __($okMessage, $replacements)
            : self::WARNING . __($warnMessage);

        $this->{$method}($message);
    }
}
