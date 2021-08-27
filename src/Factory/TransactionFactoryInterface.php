<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\CurrencyDTO;
use App\DTO\TransactionDTO;

interface TransactionFactoryInterface
{
    /**
     * @param array<string> $row row from csv file
     */
    public function createFromArray(array $row): TransactionDTO;

    public function convert(TransactionDTO $transaction, CurrencyDTO $currency): TransactionDTO;
}
