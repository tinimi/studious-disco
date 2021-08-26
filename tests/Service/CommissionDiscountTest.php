<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Factory\CurrencyFactoryInterface;
use App\Service\CommissionDiscount;
use App\Service\ExchangeRateInterface;
//use App\Factory\CurrencyFactory;
use App\Service\Math;
use App\Service\TransactionStore;
use App\Tests\AbstractMyTestCase;

class CommissionDiscountTest extends AbstractMyTestCase
{
    public function testCommission()
    {
        $transactionFactory = $this->container->get('TransactionFactory');
        $commission = new CommissionDiscount(
            new Math(),
            '0.3',
            '100',
            'EUR',
            2,
            new TransactionStore(),
            //new CurrencyFactory(),
            $this->container->get(CurrencyFactoryInterface::class),
            $transactionFactory,
            $this->container->get(ExchangeRateInterface::class)
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
