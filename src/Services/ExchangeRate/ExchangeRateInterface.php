<?php

namespace Src\Services\ExchangeRate;

interface ExchangeRateInterface
{
    public function getExchangeRateValue(string $currency): float;
}
