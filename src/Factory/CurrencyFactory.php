<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\CurrencyDTO;
use Exception;

class CurrencyFactory implements CurrencyFactoryInterface
{
    protected $curencies;

    public function __construct(array $curencies)
    {
        foreach ($curencies as $curency) {
            $name = $curency['name'];
            $scale = $curency['scale'];

            $this->curencies[$name] = new CurrencyDTO($name, $scale);
        }
    }

    public function getByName(string $name): ?CurrencyDTO
    {
        if (!isset($this->curencies[$name])) {
            throw new Exception('Currency not found');
        }

        return $this->curencies[$name];
    }
}
