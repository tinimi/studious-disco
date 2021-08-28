<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\CurrencyDTO;

interface CurrencyRepositoryInterface
{
    public function getByName(string $name): CurrencyDTO;
}
