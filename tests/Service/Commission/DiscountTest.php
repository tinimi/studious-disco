<?php

declare(strict_types=1);

namespace App\Tests\Service\Commission;

use App\Repository\CurrencyRepository;
use App\Repository\TransactionRepository;
use App\Service\Commission\Discount;
use App\Service\ExchangeRate\Stub;
use App\Service\Math;
use App\Service\TransactionStore;
use App\Tests\AbstractMyTestCase;

class DiscountTest extends AbstractMyTestCase
{
    public function testCommission(): void
    {
        $math = new Math();

        $currencyRepository = new CurrencyRepository([
            [
                'name' => 'EUR',
                'scale' => 2,
            ],
            [
                'name' => 'USD',
                'scale' => 2,
            ],
            [
                'name' => 'JPY',
                'scale' => 0,
            ],
        ]);

        $rate = new Stub([
            'EUR' => [
                'USD' => '1.1497',
                'JPY' => '129.53',
            ],
        ], $math, $currencyRepository);

        $transactionRepository = new TransactionRepository($currencyRepository, $rate, $math);

        $commission = new Discount(
            new Math(),
            '0.3',
            '100',
            'EUR',
            2,
            new TransactionStore(),
            $currencyRepository,
            $transactionRepository,
            $rate
        );

        $this->assertEquals('0.00', $commission->calc($this->createTransactionFromArray(['2014-12-31', '4', 'private', 'withdraw', '10', 'EUR'])));
        $this->assertEquals('0.00', $commission->calc($this->createTransactionFromArray(['2014-12-31', '4', 'private', 'withdraw', '10', 'EUR'])));
        $this->assertEquals('0.03', $commission->calc($this->createTransactionFromArray(['2014-12-31', '4', 'private', 'withdraw', '10', 'EUR'])));

        $this->assertEquals('0.00', $commission->calc($this->createTransactionFromArray(['2014-12-31', '3', 'private', 'withdraw', '50', 'EUR'])));
        $this->assertEquals('0.15', $commission->calc($this->createTransactionFromArray(['2014-12-31', '3', 'private', 'withdraw', '100', 'EUR'])));
        $this->assertEquals('0.30', $commission->calc($this->createTransactionFromArray(['2014-12-31', '3', 'private', 'withdraw', '100', 'EUR'])));

        $this->assertEquals('0.00', $commission->calc($this->createTransactionFromArray(['2014-12-31', '2', 'private', 'withdraw', '100', 'EUR'])));
        $this->assertEquals('30', $commission->calc($this->createTransactionFromArray(['2014-12-31', '2', 'private', 'withdraw', '10000', 'JPY'])));

        $this->assertEquals('0.00', $commission->calc($this->createTransactionFromArray(['2014-12-31', '1', 'private', 'withdraw', '50', 'EUR'])));
        $this->assertEquals('11', $commission->calc($this->createTransactionFromArray(['2014-12-31', '1', 'private', 'withdraw', '10000', 'JPY'])));
    }
}
