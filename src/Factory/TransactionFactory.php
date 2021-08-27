<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\CurrencyDTO;
use App\DTO\TransactionDTO;
use App\Service\ExchangeRateInterface;
use DateTimeImmutable;

class TransactionFactory implements TransactionFactoryInterface
{
    protected CurrencyFactoryInterface $currencyFactory;
    protected UserTypeFactoryInterface $userTypeFactory;
    protected OperationTypeFactoryInterface $operationTypeFactory;
    protected ExchangeRateInterface $rate;

    public function __construct(CurrencyFactoryInterface $currencyFactory, UserTypeFactoryInterface $userTypeFactory, OperationTypeFactoryInterface $operationTypeFactory, ExchangeRateInterface $rate)
    {
        $this->currencyFactory = $currencyFactory;
        $this->userTypeFactory = $userTypeFactory;
        $this->operationTypeFactory = $operationTypeFactory;
        $this->rate = $rate;
    }

    /**
     * @param array<string> $row row from csv file
     */
    public function createFromArray(array $row): TransactionDTO
    {
        return new TransactionDTO(
            new DateTimeImmutable($row[0]),
            $row[1],
            $this->userTypeFactory->getByName($row[2]),
            $this->operationTypeFactory->getByName($row[3]),
            $row[4],
            $this->currencyFactory->getByName($row[5])
        );
    }

    public function convert(TransactionDTO $transaction, CurrencyDTO $currency): TransactionDTO
    {
        $ratio = $this->rate->getRatio($transaction->getDate(), $transaction->getCurrency(), $currency);

        $converted = bcmul($transaction->getAmount(), $ratio, $currency->getScale());

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
