<?php

namespace Src\Services\ExchangeRate;

class ExchangeRateService implements ExchangeRateInterface
{
    private string $exchangeRateContent;

    /**
     * @param bool $hasToken
     * @param string|null $exchangeRateApiUrl
     * @throws ExchangeRateException
     */
    public function __construct(bool $hasToken = false, ?string $exchangeRateApiUrl = null)
    {
        if (!$exchangeRateApiUrl) {
            $exchangeRateApiUrl = $_ENV['EXCHANGE_RATE_API_URL'];
        }

        if ($hasToken) {
            $apiKey = $_ENV['EXCHANGE_RATE_API_KEY'];
            $exchangeRateApiUrl = $exchangeRateApiUrl . '?access_key =' . $apiKey;
        }

        $exchangeRateContent = file_get_contents($exchangeRateApiUrl);

        if (!$exchangeRateContent) {
            throw new ExchangeRateException("Exchange rate service content doesn't exist");
        }

        $this->exchangeRateContent = $exchangeRateContent;
    }

    /**
     * @param string $currency
     * @return float
     * @throws ExchangeRateException
     */
    public function getExchangeRateValue(string $currency): float
    {
        $exchangeRateArrayContent = json_decode($this->exchangeRateContent, true);

        if (!$exchangeRateArrayContent) {
            throw new ExchangeRateException("Decoded json string error for exchange rate operation");
        }

        if (array_key_exists('error', $exchangeRateArrayContent)) {
            throw new ExchangeRateException($exchangeRateArrayContent['error']['info'] ??
                                            'Exchange rate service error');
        }

        if (!isset($exchangeRateArrayContent['rates']) && !isset($exchangeRateArrayContent['rates'][$currency])) {
            throw new ExchangeRateException("Exchange 'rates' property doesn't exist");
        }

        return ceil($exchangeRateArrayContent['rates'][$currency]);
    }
}