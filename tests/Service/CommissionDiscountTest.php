<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Factory\CurrencyFactoryInterface;
use App\Factory\TransactionFactoryInterface;
use App\Service\CommissionDiscount;
use App\Service\ExchangeRate\ExchangeRateInterface;
use App\Service\Math;
use App\Service\TransactionStore;
use App\Tests\AbstractMyTestCase;

class CommissionDiscountTest extends AbstractMyTestCase
{
    public function testCommission(): void
    {
        /**
         * @var TransactionFactoryInterface
         */
        $transactionFactory = $this->container->get('TransactionFactory');
        $this->assertNotNull($transactionFactory);

        /**
         * @var CurrencyFactoryInterface
         */
        $currencyFactory = $this->container->get(CurrencyFactoryInterface::class);
        $this->assertNotNull($currencyFactory);

        /**
         * @var ExchangeRateInterface
         */
        $rate = $this->container->get(ExchangeRateInterface::class);
        $this->assertNotNull($rate);

        $commission = new CommissionDiscount(
            new Math(),
            '0.3',
            '100',
            'EUR',
            2,
            new TransactionStore(),
            $currencyFactory,
            $transactionFactory,
            $rate
        );

        $this->assertEquals('0.00', $commission->calc($transactionFactory->createFromArray(['2014-12-31', '4', 'private', 'withdraw', '10', 'EUR'])));
        $this->assertEquals('0.00', $commission->calc($transactionFactory->createFromArray(['2014-12-31', '4', 'private', 'withdraw', '10', 'EUR'])));
        $this->assertEquals('0.03', $commission->calc($transactionFactory->createFromArray(['2014-12-31', '4', 'private', 'withdraw', '10', 'EUR'])));

        $this->assertEquals('0.00', $commission->calc($transactionFactory->createFromArray(['2014-12-31', '3', 'private', 'withdraw', '50', 'EUR'])));
        $this->assertEquals('0.15', $commission->calc($transactionFactory->createFromArray(['2014-12-31', '3', 'private', 'withdraw', '100', 'EUR'])));
        $this->assertEquals('0.30', $commission->calc($transactionFactory->createFromArray(['2014-12-31', '3', 'private', 'withdraw', '100', 'EUR'])));

        $this->assertEquals('0.00', $commission->calc($transactionFactory->createFromArray(['2014-12-31', '2', 'private', 'withdraw', '100', 'EUR'])));
        $this->assertEquals('30', $commission->calc($transactionFactory->createFromArray(['2014-12-31', '2', 'private', 'withdraw', '10000', 'JPY'])));

        $this->assertEquals('0.00', $commission->calc($transactionFactory->createFromArray(['2014-12-31', '1', 'private', 'withdraw', '50', 'EUR'])));
        $this->assertEquals('11', $commission->calc($transactionFactory->createFromArray(['2014-12-31', '1', 'private', 'withdraw', '10000', 'JPY'])));
    }
}
