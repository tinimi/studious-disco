<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\TransactionDTO;
use DateTimeImmutable;

class TransactionStore implements TransactionStoreInterface
{
    /**
     * @var array<array>
     */
    protected array $store = [];

    public function store(TransactionDTO $transaction): void
    {
        $key = $this->getWeekKey($transaction->getDate(), $transaction->getUid());
        if (!isset($this->store[$key])) {
            $this->store[$key] = [];
        }
        $this->store[$key][] = $transaction;
    }

    /**
     * @return array<TransactionDTO>
     */
    public function getTransactionsByWeek(DateTimeImmutable $date, string $uid): array
    {
        $key = $this->getWeekKey($date, $uid);

        return $this->store[$key] ?? [];
    }

    protected function getWeekKey(DateTimeImmutable $date, string $uid): string
    {
        return $date->format('o-W').':'.$uid;
    }
}
