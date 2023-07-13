<?php

namespace Src\Services\Bin;

interface BinInterface
{
    public static function isEuCountryCode(string $code): bool;

    public function getBinResults(): string;

    public function getCountryCode(): string;
}