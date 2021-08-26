<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\TransactionDTO;
use Exception;

class CommissionCalc implements CommissionCalcInterface
{
    protected $commissions;

    public function __construct($commissions)
    {
        $this->commissions = $commissions;
    }

    public function calc(TransactionDTO $transaction): string
    {
        if (!isset($this->commissions[$transaction->getOperationType()->getName()])) {
            throw new Exception('Unknown operation type');
        }
        if (!isset($this->commissions[$transaction->getOperationType()->getName()][$transaction->getUserType()->getName()])) {
            throw new Exception('Unknown operation-user type');
        }

        return $this->commissions[$transaction->getOperationType()->getName()][$transaction->getUserType()->getName()]->calc($transaction);
    }
}
