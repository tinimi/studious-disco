<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\CurrencyDTO;
use Exception;

class CurrencyFactory implements CurrencyFactoryInterface
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
            throw new Exception('Currency not found');
        }

        return $this->currencies[$name];
    }
}
