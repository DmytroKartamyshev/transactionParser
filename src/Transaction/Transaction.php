<?php

namespace Src\Transaction;

use Src\Services\Bin\BinException;
use Src\Services\Bin\BinInterface;
use Src\Services\Bin\BinService;
use Src\Services\ExchangeRate\ExchangeRateException;
use Src\Services\ExchangeRate\ExchangeRateInterface;

class Transaction
{
    /**
     * @var string
     */
    protected string $currency;

    /**
     * @var float
     */
    protected float $binCode;

    /**
     * @var int
     */
    protected int $amount;


    /**
     * @param int $binCode
     * @param float $amount
     * @param string $currency
     */
    public function __construct(int $binCode, float $amount, string $currency)
    {
        $this->binCode = $binCode;
        $this->currency = $currency;
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getBinCode(): int
    {
        return $this->binCode;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param BinInterface $bin
     * @param ExchangeRateInterface $exchangeRate
     * @throws BinException
     * @throws ExchangeRateException
     */
    public function calcCommissionAmount(BinInterface $bin, ExchangeRateInterface $exchangeRate): ?float
    {
        $countryCode = $bin->getCountryCode();
        $isEuCode = BinService::isEuCountryCode($countryCode);

        $exchangeRateValue = $exchangeRate->getExchangeRateValue($this->currency);

        if ($this->currency == 'EUR' || $exchangeRateValue == 0) {
            return $this->amount * ($isEuCode ? 0.01 : 0.02);
        }

        if ($exchangeRateValue > 0) {
            return ($this->amount / $exchangeRateValue) * ($isEuCode ? 0.01 : 0.02);
        }

        return null;
    }
}