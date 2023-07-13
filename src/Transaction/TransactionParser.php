<?php

namespace Src\Transaction;


class TransactionParser
{
    /**
     * @var string
     */
    protected string $inputFileName;

    /**
     * @var array
     */
    private array $transactions = [];

    /**
     * @param string|null $inputFileName
     * @throws TransactionParserException
     */
    public function __construct(?string $inputFileName)
    {
        if (empty($inputFileName) || !file_exists($inputFileName)) {
            throw new TransactionParserException("Transaction parser won't work, because file doesn't exist");
        }

        $this->inputFileName = $inputFileName;
    }

    /**
     * @throws TransactionParserException
     */
    public function parse(): array
    {
        try {
            foreach ($this->getLines($this->inputFileName) as $n => $line) {
                $transactionData = json_decode($line, true);
                ['bin' => $bin, 'amount' => $amount, 'currency' => $currency] = $transactionData;
                $this->transactions[] = new Transaction($bin, $amount, $currency);
            }

            return $this->transactions;
        } catch (\Exception $e) {
            throw new TransactionParserException($e->getMessage());
        }
    }

    /**
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param $file
     * @return \Generator
     * @throws TransactionParserException
     */
    private function getLines($file): \Generator
    {
        $f = fopen($file, 'r');

        try {
            while ($line = fgets($f)) {
                yield $line;
            }
        } catch (\Exception $e) {
            throw new TransactionParserException($e->getMessage());
        } finally {
            fclose($f);
        }
    }
}