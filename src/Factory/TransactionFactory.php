<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\CurrencyDTO;
use App\DTO\TransactionDTO;
use App\Service\ExchangeRate\ExchangeRateInterface;
use App\Service\Math;
use DateTimeImmutable;

class TransactionFactory implements TransactionFactoryInterface
{
    protected CurrencyFactoryInterface $currencyFactory;
    protected ExchangeRateInterface $rate;
    protected Math $math;

    public function __construct(CurrencyFactoryInterface $currencyFactory, ExchangeRateInterface $rate, Math $math)
    {
        $this->currencyFactory = $currencyFactory;
        $this->rate = $rate;
        $this->math = $math;
    }

    /**
     * @param array<string> $row row from csv file
     */
    public function createFromArray(array $row): TransactionDTO
    {
        return new TransactionDTO(
            new DateTimeImmutable($row[0]),
            $row[1],
            $row[2],
            $row[3],
            $row[4],
            $this->currencyFactory->getByName($row[5])
        );
    }

    public function convert(TransactionDTO $transaction, CurrencyDTO $currency): TransactionDTO
    {
        $ratio = $this->rate->getRatio($transaction->getDate(), $transaction->getCurrency(), $currency);

        $converted = $this->math->mul($transaction->getAmount(), $ratio, $currency->getScale());

        return new TransactionDTO(
            $transaction->getDate(),
            $transaction->getUid(),
            $transaction->getUserType(),
            $transaction->getOperationType(),
            $converted,
            $currency
        );
    }
}
