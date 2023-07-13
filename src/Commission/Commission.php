<?php

namespace Src\Commission;

use Src\Transaction\Transaction;

class Commission
{
    private $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }


}