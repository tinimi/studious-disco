<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\TransactionDTO;

abstract class AbstractCommission
{
    abstract public function calc(TransactionDTO $transaction): string;

    protected function calcCommission(string $amount, string $comission, int $scale): string
    {
        $percent = bcdiv($amount, '100', $scale + 2);

        return bcmul($percent, $this->comission, $scale);
    }
}
