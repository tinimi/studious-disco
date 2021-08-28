<?php

declare(strict_types=1);

namespace App\Service\ExchangeRate;

use App\DTO\CurrencyDTO;
use DateTimeImmutable;

interface ExchangeRateInterface
{
    public function getRatio(DateTimeImmutable $date, CurrencyDTO $from, CurrencyDTO $to): string;
}
