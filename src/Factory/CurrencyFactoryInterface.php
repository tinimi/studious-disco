<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\CurrencyDTO;

interface CurrencyFactoryInterface
{
    public function getByName(string $name): CurrencyDTO;
}
