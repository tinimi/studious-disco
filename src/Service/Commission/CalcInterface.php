<?php

declare(strict_types=1);

namespace App\Service\Commission;

use App\DTO\TransactionDTO;

interface CalcInterface
{
    public function calc(TransactionDTO $transaction): string;
}
