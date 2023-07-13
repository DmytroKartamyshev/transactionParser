<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Src\Services\ExchangeRate\ExchangeRateService;
use Src\Services\ExchangeRate\ExchangeRateException;

final class ExchangeRateServiceTest extends TestCase
{
    public static function envValueProvider(): array
    {
        return [
            [
                'https://api.exchangeratesapi.io/latest'
            ]
        ];
    }

    /**
     * @dataProvider envValueProvider
     */
    public function testCanBeCreatedFromExistingSource(string $apiUrl): void
    {
        $mock = $this->getMockBuilder(ExchangeRateService::class)
            ->setConstructorArgs([false, $apiUrl])
            ->getMock();

        $this->assertInstanceOf(
            ExchangeRateService::class,
            $mock
        );
    }

    public function testCanNotBeCreatedFromExistingSource(): void
    {
        $this->expectException(ExchangeRateException::class);
        $this->expectExceptionMessage("Exchange rate service content doesn't exist");

        new ExchangeRateService(false, 'example.com');
    }

    /**
     * @dataProvider envValueProvider
     */
    public function testApiTokenDoesNotExist(string $apiUrl): void
    {
        $this->expectException(ExchangeRateException::class);

        $exchangeRateService = new ExchangeRateService(false, $apiUrl);
        $exchangeRateService->getExchangeRateValue('JPY');
    }
}