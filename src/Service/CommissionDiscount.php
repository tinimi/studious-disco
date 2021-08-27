<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CurrencyDTO;
use App\DTO\TransactionDTO;
use App\Factory\CurrencyFactoryInterface;
use App\Factory\TransactionFactoryInterface;
use DivisionByZeroError;

class CommissionDiscount extends AbstractCommission
{
    protected string $commission;
    protected string $discountAmount;
    protected CurrencyDTO $currency;
    protected int $discountCount;

    protected TransactionStoreInterface $store;
    protected TransactionFactoryInterface $transactionFactory;
    protected ExchangeRateInterface $rate;

    public function __construct(Math $math, string $commission, string $discountAmount, string $discountCurrency, int $discountCount,
        TransactionStoreInterface $store, CurrencyFactoryInterface $currencyFactory, TransactionFactoryInterface $transactionFactory, ExchangeRateInterface $rate)
    {
        parent::__construct($math);
        $this->commission = $commission;
        $this->discountAmount = $discountAmount;
        $this->discountCount = $discountCount;

        $this->store = $store;
        $this->currency = $currencyFactory->getByName($discountCurrency);
        $this->transactionFactory = $transactionFactory;
        $this->rate = $rate;
    }

    public function calc(TransactionDTO $transaction): string
    {
        $transactions = $this->store->getTransactionsByWeek($transaction->getDate(), $transaction->getUid());

        if (count($transactions) >= $this->discountCount) {
            return $this->calcCommission($transaction->getAmount(), $this->commission, $transaction->getCurrency()->getScale());
        }

        $converted = $this->transactionFactory->convert($transaction, $this->currency);
        $this->store->store($converted);

        $stored = '0';
        foreach ($transactions as $storedTransaction) {
            $stored = $this->math->add($stored, $storedTransaction->getAmount(), $this->currency->getScale());
        }

        if (bccomp($stored, $this->discountAmount, $this->currency->getScale()) >= 0) {
            return $this->calcCommission($transaction->getAmount(), $this->commission, $transaction->getCurrency()->getScale());
        }

        $sum = $this->math->add($stored, $converted->getAmount(), $this->currency->getScale());
        if (bccomp($sum, $this->discountAmount, $this->currency->getScale()) < 0) {
            return $this->math->getZero($transaction->getCurrency()->getScale());
        }

        $overflow = bcsub($sum, $this->discountAmount, $this->currency->getScale());

        if ($transaction->getCurrency()->getName() !== $this->currency->getName()) {
            $ratio = $this->rate->getRatio($transaction->getDate(), $transaction->getCurrency(), $this->currency);
            $overflow = bcdiv($overflow, $ratio, $transaction->getCurrency()->getScale());
        }

        return $this->calcCommission($overflow, $this->commission, $transaction->getCurrency()->getScale());
    }
}
