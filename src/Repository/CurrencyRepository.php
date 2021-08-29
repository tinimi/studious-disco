<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\CurrencyDTO;
use App\Exceptions\CurrencyNotFoundException;
use App\Exceptions\InvalidCurrencyFormatException;

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
            if (!isset($currency['name'])) {
                throw new InvalidCurrencyFormatException('Currency name not found');
            }
            if (!isset($currency['scale'])) {
                throw new InvalidCurrencyFormatException('Currency scale not found');
            }
            if (!preg_match('/^[a-zA-Z]+$/', $currency['name'])) {
                throw new InvalidCurrencyFormatException('Invalid currency name: '.$currency['name']);
            }
            if ($currency['scale'] < 0) {
                throw new InvalidCurrencyFormatException(sprintf('Invalid currency (%s) scale: %s', $currency['name'], $currency['scale']));
            }
            $name = $currency['name'];
            $scale = $currency['scale'];

            $this->currencies[$name] = new CurrencyDTO($name, $scale);
        }
    }

    public function getByName(string $name): CurrencyDTO
    {
        if (!isset($this->currencies[$name])) {
            throw new CurrencyNotFoundException('Currency not found: '.$name);
        }

        return $this->currencies[$name];
    }
}
