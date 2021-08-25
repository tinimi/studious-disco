<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CurrencyDTO;
use DateTimeImmutable;
use Exception;

class ExchangeRateStub implements ExchangeRateInterface
{
    protected array $rates;
    protected Math $math;

    public function __construct(array $rates, Math $math)
    {
        $this->rates = $rates;
        $this->math = $math;
    }

    public function getRatio(DateTimeImmutable $date, CurrencyDTO $from, CurrencyDTO $to): string
    {
        if ($from->getName() === $to->getName()) {
            return '1';
        }
        if (isset($this->rates[$from->getName()]) && isset($this->rates[$from->getName()][$to->getName()])) {
            return $this->rates[$from->getName()][$to->getName()];
        }
        if (isset($this->rates[$to->getName()]) && isset($this->rates[$to->getName()][$from->getName()])) {
            return bcdiv('1', $this->rates[$to->getName()][$from->getName()], 10);
        }

        throw new Exception('Can\'t get exchange ratio');
    }
}
