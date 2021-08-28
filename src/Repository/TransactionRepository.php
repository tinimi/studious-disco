<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\CurrencyDTO;
use App\DTO\TransactionDTO;
use App\Exceptions\InvalidFormatException;
use App\Service\ExchangeRate\ExchangeRateInterface;
use App\Service\Math;
use DateTimeImmutable;
use Exception;

class TransactionRepository implements TransactionRepositoryInterface
{
    protected CurrencyRepositoryInterface $currencyRepository;
    protected ExchangeRateInterface $rate;
    protected Math $math;

    public function __construct(CurrencyRepositoryInterface $currencyRepository, ExchangeRateInterface $rate, Math $math)
    {
        $this->currencyRepository = $currencyRepository;
        $this->rate = $rate;
        $this->math = $math;
    }

    /**
     * @param array<string> $row row from csv file
     */
    public function createFromArray(array $row): TransactionDTO
    {
        if (count($row) !== 6) {
            throw new InvalidFormatException('Must be 6 fields in a row');
        }
        try {
            $date = new DateTimeImmutable($row[0]);
        } catch (Exception $e) {
            throw new InvalidFormatException('Invalid date format');
        }
        if (!$this->math->isWellFormed($row[4])) {
            throw new InvalidFormatException('Invalid amount format');
        }

        return new TransactionDTO(
            $date,
            $row[1],
            $row[2],
            $row[3],
            $row[4],
            $this->currencyRepository->getByName($row[5])
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
