<?php

namespace LaravelSecurityCheck\Scanners;

interface ScannerInterface
{
    public function execute(): void;
}
