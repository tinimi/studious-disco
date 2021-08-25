<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\TransactionDTO;

interface ComissionCalcInterface
{
    public function calc(TransactionDTO $transaction): string;
}
