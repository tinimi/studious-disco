<?php

declare(strict_types=1);

namespace App\Service\ExchangeRate;

use App\DTO\CurrencyDTO;
use App\Exceptions\RateException;
use App\Exceptions\RateInvalidException;
use App\Exceptions\RateNotFoundException;
use App\Repository\CurrencyRepositoryInterface;
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
    public function __construct(array $rates, Math $math, CurrencyRepositoryInterface $currencyRepository)
    {
        foreach ($rates as $currency1 => $rates1) {
            if (!is_array($rates1)) {
                throw new RateException('Invalid rates config');
            }
            $currencyRepository->getByName($currency1);
            foreach ($rates1 as $currency2 => $rate) {
                $currencyRepository->getByName($currency2);
                if (!$math->isWellFormed($rate) || $math->isZero($rate)) {
                    throw new RateInvalidException("Invalid rate for $currency1 -> $currency2 conversion ($rate)");
                }
            }
        }
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

        throw new RateNotFoundException(sprintf('Ratio not found for %s->%s conversion', $from->getName(), $to->getName()));
    }
}
