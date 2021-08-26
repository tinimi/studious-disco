<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\TransactionDTO;

class CommissionConstant extends AbstractCommission
{
    protected string $commission;

    public function __construct(Math $math, string $commission)
    {
        parent::__construct($math);
        $this->commission = $commission;
    }

    public function calc(TransactionDTO $transaction): string
    {
        return $this->calcCommission($transaction->getAmount(), $this->commission, $transaction->getCurrency()->getScale());
    }
}
