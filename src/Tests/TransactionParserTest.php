<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Src\Transaction\Transaction;
use Src\Transaction\TransactionParser;
use Src\Transaction\TransactionParserException;

final class TransactionParserTest extends TestCase
{
    public function testCanBeCreatedFromExistingFile(): void
    {
        $filename = 'input.txt';

        $mock = $this->getMockBuilder(TransactionParser::class)
            ->setConstructorArgs([$filename])
            ->getMock();

        $this->assertInstanceOf(
            TransactionParser::class,
            $mock
        );
    }

    public function testCanNotBeCreatedFromInvalidFileName(): void
    {
        $this->expectException(TransactionParserException::class);
        new TransactionParser('invalid.txt');
    }

    public function testParseAllTransactions(): void
    {
        $transactionParser = new TransactionParser('input.txt');
        $transactions = $transactionParser->parse();

        $this->assertIsArray($transactions);
        $this->assertCount(5, $transactions);
        $this->assertContainsOnlyInstancesOf(Transaction::class, $transactions);
    }

    public function testParseSingleTransaction(): void
    {
        $transactionParser = new TransactionParser('input.txt');
        $transactions = $transactionParser->parse();

        $this->assertIsArray($transactions);
        $this->assertCount(5, $transactions);

        $firstTransaction = $transactions[0];
        $this->assertInstanceOf(Transaction::class, $firstTransaction);
        $this->assertEquals('45717360', $firstTransaction->getBinCode());
    }
}