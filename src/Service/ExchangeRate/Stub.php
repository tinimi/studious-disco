<?php

declare(strict_types=1);

namespace App\Service\ExchangeRate;

use App\DTO\CurrencyDTO;
use App\Exceptions\RateException;
use App\Service\Math;
use DateTimeImmutable;

class Stub implements ExchangeRateInterface
{
    /**
     * @var array<array>
     */
    protected array $rates;
    protected Math $math;

    /**
     * @param array<array> $rates
     */
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
            return $this->math->div('1', $this->rates[$to->getName()][$from->getName()], 10);
        }

        throw new RateException('Can\'t get exchange ratio');
    }
}
