<?php

declare(strict_types=1);

namespace App\Service\Commission;

use App\DTO\CurrencyDTO;
use App\DTO\TransactionDTO;
use App\Exceptions\InvalidCommissionException;
use App\Exceptions\InvalidDiscountException;
use App\Repository\CurrencyRepositoryInterface;
use App\Repository\TransactionRepositoryInterface;
use App\Service\ExchangeRate\ExchangeRateInterface;
use App\Service\Math;
use App\Service\TransactionStoreInterface;

class Discount extends AbstractCommission
{
    protected string $commission;
    protected string $discountAmount;
    protected CurrencyDTO $currency;
    protected int $discountCount;

    protected TransactionStoreInterface $store;
    protected TransactionRepositoryInterface $transactionRepository;
    protected ExchangeRateInterface $rate;

    public function __construct(Math $math, string $commission, string $discountAmount, string $discountCurrency, int $discountCount,
        TransactionStoreInterface $store, CurrencyRepositoryInterface $currencyRepository, TransactionRepositoryInterface $transactionRepository, ExchangeRateInterface $rate)
    {
        if (!$math->isWellFormed($commission)) {
            throw new InvalidCommissionException('Invalid commission: '.$commission);
        }
        if (!$math->isWellFormed($discountAmount)) {
            throw new InvalidDiscountException('Invalid discount ammount: '.$discountAmount);
        }

        if ($discountCount < 0) {
            throw new InvalidDiscountException('Invalid discount count: '.$discountCount);
        }

        parent::__construct($math);

        $this->commission = $commission;
        $this->discountAmount = $discountAmount;
        $this->discountCount = $discountCount;

        $this->store = $store;
        $this->currency = $currencyRepository->getByName($discountCurrency);
        $this->transactionRepository = $transactionRepository;
        $this->rate = $rate;
    }

    public function calc(TransactionDTO $transaction): string
    {
        $transactions = $this->store->getTransactionsByWeek($transaction->getDate(), $transaction->getUid());

        if (count($transactions) >= $this->discountCount) {
            return $this->calcCommission($transaction->getAmount(), $this->commission, $transaction->getCurrency()->getScale());
        }

        $converted = $this->transactionRepository->convert($transaction, $this->currency);
        $this->store->store($converted);

        $stored = '0';
        foreach ($transactions as $storedTransaction) {
            $stored = $this->math->add($stored, $storedTransaction->getAmount(), $this->currency->getScale());
        }

        if ($this->math->comp($stored, $this->discountAmount, $this->currency->getScale()) >= 0) {
            return $this->calcCommission($transaction->getAmount(), $this->commission, $transaction->getCurrency()->getScale());
        }

        $sum = $this->math->add($stored, $converted->getAmount(), $this->currency->getScale());
        if ($this->math->comp($sum, $this->discountAmount, $this->currency->getScale()) < 0) {
            return $this->math->getZero($transaction->getCurrency()->getScale());
        }

        $overflow = $this->math->sub($sum, $this->discountAmount, $this->currency->getScale());

        if ($transaction->getCurrency()->getName() !== $this->currency->getName()) {
            $ratio = $this->rate->getRatio($transaction->getDate(), $transaction->getCurrency(), $this->currency);
            $overflow = $this->math->div($overflow, $ratio, $transaction->getCurrency()->getScale());
        }

        return $this->calcCommission($overflow, $this->commission, $transaction->getCurrency()->getScale());
    }
}
