<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\TransactionDTO;

abstract class AbstractCommission
{
    protected $math;

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
