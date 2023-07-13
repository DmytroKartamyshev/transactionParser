<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Src\Transaction\Transaction;
use Src\Services\Bin\BinService;
use Src\Services\ExchangeRate\ExchangeRateService;

final class TransactionTest extends TestCase
{
    public function testCalcCommissionAmount(): void
    {
        $binCode = 45717360;
        $binApiUrl = 'https://lookup.binlist.net/';
        $exchangeRateApiUrl = 'https://api.exchangeratesapi.io/latest';
        $transaction = new Transaction($binCode, 100.00, 'EUR');

        $mockBinService = $this->getMockBuilder(BinService::class)
            ->setConstructorArgs([$binCode, $binApiUrl])
            ->getMock();

        $mockExchangeRateService = $this->getMockBuilder(ExchangeRateService::class)
            ->setConstructorArgs([false, $exchangeRateApiUrl])
            ->getMock();

        $commission = $transaction->calcCommissionAmount($mockBinService, $mockExchangeRateService);

        $this->assertEquals(2.0, $commission);
    }
}