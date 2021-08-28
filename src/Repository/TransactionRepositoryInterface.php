<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\CurrencyDTO;
use App\DTO\TransactionDTO;

interface TransactionRepositoryInterface
{
    /**
     * @param array<string> $row row from csv file
     */
    public function createFromArray(array $row): TransactionDTO;

    public function convert(TransactionDTO $transaction, CurrencyDTO $currency): TransactionDTO;
}
