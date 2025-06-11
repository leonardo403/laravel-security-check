<?php

namespace Elohim\LaravelSecurityCheck\Scanners;

interface ScannerInterface
{
    public function execute(): void;
}
