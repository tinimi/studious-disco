<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\TransactionDTO;
use Exception;

class CommissionCalc implements CommissionCalcInterface
{
    /**
     * @var array<array>
     */
    protected array $commissions;

    /**
     * @param array<array> $commissions
     */
    public function __construct(array $commissions)
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
