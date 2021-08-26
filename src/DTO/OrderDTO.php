<?php

declare(strict_types=1);

namespace App\DTO;

class OrderDTO
{
    protected int $line;
    protected TransactionDTO $transaction;
    protected string $commission;

    public function __construct(int $line, TransactionDTO $transaction)
    {
        $this->line = $line;
        $this->transaction = $transaction;
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public function getTransaction(): TransactionDTO
    {
        return $this->transaction;
    }

    public function setCommission(string $commission): void
    {
        $this->commission = $commission;
    }

    public function getCommission(): string
    {
        return $this->commission;
    }
}
