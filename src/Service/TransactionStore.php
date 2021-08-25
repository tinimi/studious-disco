<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\TransactionDTO;
use DateTimeImmutable;

class TransactionStore implements TransactionStoreInterface
{
    protected $store = [];

    public function store(TransactionDTO $transaction)
    {
        $key = $this->getWeekKey($transaction->getDate(), $transaction->getUid());
        if (!isset($this->store[$key])) {
            $this->store[$key] = [];
        }
        $this->store[$key][] = $transaction;
    }

    public function getTransactionsByWeek(DateTimeImmutable $date, string $uid)
    {
        $key = $this->getWeekKey($date, $uid);

        return isset($this->store[$key]) ? $this->store[$key] : [];
    }

    protected function getWeekKey(DateTimeImmutable $date, string $uid)
    {
        return $date->format('o-W').':'.$uid;
    }
}
