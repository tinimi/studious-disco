<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CurrencyDTO;
use BenMajor\ExchangeRatesAPI\ExchangeRatesAPI;
use DateTimeImmutable;
use Exception;

class ExchangeRateApi implements ExchangeRateInterface
{
    protected ExchangeRatesAPI $api;
    protected bool $isPaid;
    protected Math $math;

    public function __construct(ExchangeRatesAPI $api, bool $isPaid, Math $math)
    {
        $this->api = $api;
        $this->isPaid = $isPaid;
        $this->math = $math;
    }

    public function getRatio(DateTimeImmutable $date, CurrencyDTO $from, CurrencyDTO $to): string
    {
        if ($from->getName() === $to->getName()) {
            return '1';
        }

        if (!$this->isPaid) {
            if (('EUR' !== $from->getName()) && ('EUR' === $to->getName())) {
                return $this->math->div('1', $this->getRatio($date, $to, $from), 10);
            }
            if ('EUR' !== $from->getName()) {
                throw new Exception('Invalid conversion on free plan');
            }
        }

        $response = $this->api->setFetchDate($date->format('Y-m-d'))->setBaseCurrency($from->getName())->addRate($to->getName())->fetch(true);

        return (string) $response->rates->{$to->getName()};
    }
}
