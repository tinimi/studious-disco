<?php

declare(strict_types=1);

namespace App\Service\ExchangeRate;

use App\DTO\CurrencyDTO;
use DateTimeImmutable;

class CacheMemory implements ExchangeRateInterface
{
    protected ExchangeRateInterface $rate;

    /**
     * @var array<array>
     */
    protected array $cache = [];

    public function __construct(ExchangeRateInterface $rate)
    {
        $this->rate = $rate;
    }

    public function getRatio(DateTimeImmutable $date, CurrencyDTO $from, CurrencyDTO $to): string
    {
        $dateStr = $date->format('Y-m-d');
        $fromStr = $from->getName();
        $toStr = $to->getName();

        $this->cache[$dateStr] = $this->cache[$dateStr] ?? [];
        $this->cache[$dateStr][$fromStr] = $this->cache[$dateStr][$fromStr] ?? [];
        if (!isset($this->cache[$dateStr][$fromStr][$toStr])) {
            $this->cache[$dateStr][$fromStr][$toStr] = $this->rate->getRatio($date, $from, $to);
        }

        return $this->cache[$dateStr][$fromStr][$toStr];
    }
}
