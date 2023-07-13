<?php

namespace Src\Services\Bin;

class BinService implements BinInterface
{
    private int $binCode;

    private string $binContent;

    /**
     * @param int $binCode
     * @param string|null $binApiLink
     * @throws BinException
     */
    public function __construct(int $binCode, ?string $binApiLink = null)
    {
        $this->binCode = $binCode;

        if (!$binApiLink) {
            $binApiLink = $_ENV['BIN_API_URL'];
        }

        $binContent = file_get_contents($binApiLink . $this->binCode);

        if (!$binContent) {
            throw new BinException("Bin content doesn't exist");
        }

        $this->binContent = $binContent;
    }

    public static function isEuCountryCode(string $code): bool
    {
        $countries = ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU',
                     'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'];

        return in_array($code, $countries);
    }

    public function getBinResults(): string
    {
        return $this->binContent;
    }

    /**
     * @return string
     * @throws BinException
     */
    public function getCountryCode(): string
    {
        $binContentArray = json_decode($this->binContent, true);

        if (!$binContentArray) {
            throw new BinException("Decoded json string error for country code operation");
        }

        if (!isset($binContentArray['country']) || !isset($binContentArray['country']['alpha2'])) {
            throw new BinException("Country code property doesn't exist");
        }

        return $binContentArray['country']['alpha2'];
    }
}