<?php

declare(strict_types=1);

namespace App\Service\ExchangeRate;

use App\DTO\CurrencyDTO;
use App\Exceptions\RateSelectorException;
use DateTimeImmutable;

class Selector implements ExchangeRateInterface
{
    protected ExchangeRateInterface $rate;

    /**
     * @var array<array>
     */
    protected array $cache = [];

    /**
     * @param array<ExchangeRateInterface> $rates
     */
    public function __construct(string $selectedRateName, array $rates)
    {
        if (!isset($rates[$selectedRateName])) {
            throw new RateSelectorException('Invalid APP_RATE_MODULE');
        }
        $this->rate = $rates[$selectedRateName];
    }

    public function getRatio(DateTimeImmutable $date, CurrencyDTO $from, CurrencyDTO $to): string
    {
        return $this->rate->getRatio($date, $from, $to);
    }
}
