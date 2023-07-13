<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Src\Services\Bin\BinService;
use Src\Services\Bin\BinException;

final class BinServiceTest extends TestCase
{
    public static function envValueProvider(): array
    {
        return [
            [
                'https://lookup.binlist.net/'
            ]
        ];
    }

    /**
     * @dataProvider envValueProvider
     */
    public function testCanBeCreatedFromExistingSource(string $binApiUri): void
    {
        $binCode = 45717360;

        $mock = $this->getMockBuilder(BinService::class)
            ->setConstructorArgs([$binCode, $binApiUri])
            ->getMock();

        $this->assertInstanceOf(
            BinService::class,
            $mock
        );
    }

    public function testCanNotBeCreatedFromExistingSource(): void
    {
        $this->expectException(BinException::class);
        $this->expectExceptionMessage("Bin content doesn't exist");

        new BinService(45717360, 'example.com');
    }

    public function testIsEuCountryCode(): void
    {
        $this->assertSame(true, BinService::isEuCountryCode('DK'));
    }

    /**
     * @dataProvider envValueProvider
     */
    public function testGetBinResults(string $binApiUri): void
    {
        $binService = new BinService(45717360, $binApiUri);
        $binContent = $binService->getBinResults();

        $data = json_decode($binContent, true);
        $this->assertIsArray($data);
    }

    /**
     * @dataProvider envValueProvider
     */
    public function testGetCountryCode(string $binApiUri): void
    {
        $binService = new BinService(45717360, $binApiUri);

        $countryCode = $binService->getCountryCode();

        $this->assertSame('DK', $countryCode);
    }
}