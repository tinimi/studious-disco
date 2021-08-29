<?php

declare(strict_types=1);

namespace App\Service\Commission;

use App\DTO\TransactionDTO;
use App\Exceptions\InvalidCommissionException;
use App\Service\Math;

class Constant extends AbstractCommission
{
    protected string $commission;

    public function __construct(Math $math, string $commission)
    {
        parent::__construct($math);

        if (!$math->isWellFormed($commission)) {
            throw new InvalidCommissionException('Invalid commission: '.$commission);
        }
        $this->commission = $commission;
    }

    public function calc(TransactionDTO $transaction): string
    {
        return $this->calcCommission($transaction->getAmount(), $this->commission, $transaction->getCurrency()->getScale());
    }
}
