<?php

declare(strict_types=1);

namespace App\Service\Commission;

use App\DTO\TransactionDTO;
use App\Exceptions\CommissionCalcException;

class Calc implements CalcInterface
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
        if (!isset($this->commissions[$transaction->getOperationType()])) {
            throw new CommissionCalcException('Unknown operation type');
        }
        if (!isset($this->commissions[$transaction->getOperationType()][$transaction->getUserType()])) {
            throw new CommissionCalcException('Unknown operation-user type');
        }

        return $this->commissions[$transaction->getOperationType()][$transaction->getUserType()]->calc($transaction);
    }
}
