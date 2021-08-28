<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeImmutable;

class TransactionDTO
{
    protected DateTimeImmutable $date;
    protected string $uid;
    protected string $userType;
    protected string $operationType;
    protected string $amount;
    protected CurrencyDTO $currency;

    public function __construct(
        DateTimeImmutable $date,
        string $uid,
        string $userType,
        string $operationType,
        string $amount,
        CurrencyDTO $currency
    ) {
        $this->date = $date;
        $this->uid = $uid;
        $this->userType = $userType;
        $this->operationType = $operationType;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function getOperationType(): string
    {
        return $this->operationType;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): CurrencyDTO
    {
        return $this->currency;
    }
}
