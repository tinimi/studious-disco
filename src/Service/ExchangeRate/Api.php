<?php

declare(strict_types=1);

namespace App\Service\ExchangeRate;

use App\DTO\CurrencyDTO;
use App\Exceptions\RateException;
use App\Exceptions\RateNotFoundException;
use App\Repository\CurrencyRepositoryInterface;
use App\Service\Math;
use BenMajor\ExchangeRatesAPI\ExchangeRatesAPI;
use DateTimeImmutable;

class Api implements ExchangeRateInterface
{
    protected ExchangeRatesAPI $api;
    protected bool $isPaid;
    protected Math $math;
    protected string $baseCurrency;

    public function __construct(ExchangeRatesAPI $api, bool $isPaid, Math $math, string $baseCurrency, CurrencyRepositoryInterface $currencyRepository)
    {
        $this->api = $api;
        $this->isPaid = $isPaid;
        $this->math = $math;
        $this->baseCurrency = $baseCurrency;
        $currencyRepository->getByName($baseCurrency);
    }

    public function getRatio(DateTimeImmutable $date, CurrencyDTO $from, CurrencyDTO $to): string
    {
        if ($from->getName() === $to->getName()) {
            return '1';
        }

        if (!$this->isPaid) {
            if (($this->baseCurrency !== $from->getName()) && ($this->baseCurrency === $to->getName())) {
                return $this->math->div('1', $this->getRatio($date, $to, $from), 10);
            }
            if ($this->baseCurrency !== $from->getName()) {
                throw new RateException('Invalid conversion on free plan');
            }
        }

        $response = @$this->api->setFetchDate($date->format('Y-m-d'))->setBaseCurrency($from->getName())->addRate($to->getName())->fetch(true);
        if (!isset($response->rates)) {
            throw new RateNotFoundException(sprintf('Ratio not found for %s->%s conversion', $from->getName(), $to->getName()));
        }

        return (string) $response->rates->{$to->getName()};
    }
}
