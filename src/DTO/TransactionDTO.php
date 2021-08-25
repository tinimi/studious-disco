<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeImmutable;

class TransactionDTO
{
    protected DateTimeImmutable $date;
    protected string $uid;
    protected UserTypeDTO $userType;
    protected OperationTypeDTO $operationType;
    protected string $amount;
    protected CurrencyDTO $currency;

    public function __construct(
        DateTimeImmutable $date,
        string $uid,
        UserTypeDTO $userType,
        OperationTypeDTO $operationType,
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

    public function getUserType(): UserTypeDTO
    {
        return $this->userType;
    }

    public function getOperationType(): OperationTypeDTO
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
