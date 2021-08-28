<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\CurrencyDTO;
use App\Exceptions\CurrencyNotFoundException;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    /**
     * @var array<CurrencyDTO>
     */
    protected array $currencies;

    /**
     * @param array<array> $currencies
     */
    public function __construct(array $currencies)
    {
        foreach ($currencies as $currency) {
            $name = $currency['name'];
            $scale = $currency['scale'];

            $this->currencies[$name] = new CurrencyDTO($name, $scale);
        }
    }

    public function getByName(string $name): CurrencyDTO
    {
        if (!isset($this->currencies[$name])) {
            throw new CurrencyNotFoundException('Currency not found');
        }

        return $this->currencies[$name];
    }
}
