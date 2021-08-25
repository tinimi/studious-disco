<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\TransactionDTO;

class CommissionConstant extends AbstractCommission
{
    protected string $comission;

    public function __construct($comission)
    {
        $this->comission = $comission;
    }

    public function calc(TransactionDTO $transaction): string
    {
        return $this->calcCommission($transaction->getAmount(), $this->comission, $transaction->getCurrency()->getScale());
    }
}
