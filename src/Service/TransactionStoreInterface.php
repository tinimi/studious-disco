<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\TransactionDTO;
use DateTimeImmutable;

interface TransactionStoreInterface
{
    public function store(TransactionDTO $transaction);

    public function getTransactionsByWeek(DateTimeImmutable $date, string $uid);
}
