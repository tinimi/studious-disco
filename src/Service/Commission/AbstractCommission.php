<?php

declare(strict_types=1);

namespace App\Service\Commission;

use App\DTO\TransactionDTO;
use App\Service\Math;

abstract class AbstractCommission
{
    protected Math $math;

    abstract public function calc(TransactionDTO $transaction): string;

    public function __construct(Math $math)
    {
        $this->math = $math;
    }

    protected function calcCommission(string $amount, string $commission, int $scale): string
    {
        $percent = $this->math->div($amount, '100', $scale + 2);

        return $this->math->mul($percent, $commission, $scale);
    }
}
