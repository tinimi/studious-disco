<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\CommissionCalc;
use App\Service\CommissionConstant;
use App\Tests\AbstractMyTestCase;
use Exception;

class CommissionCalcTest extends AbstractMyTestCase
{
    public function testNotConfigured()
    {
        $this->expectException(Exception::class);

        $calc = new CommissionCalc([]);

        $transactionFactory = $this->container->get('TransactionFactory');
        $transaction = $transactionFactory->createFromArray(['2014-12-31', '4', 'private', 'withdraw', '1200', 'EUR']);

        $calc->calc($transaction);
    }

    public function testNotConfigured2()
    {
        $this->expectException(Exception::class);

        $calc = new CommissionCalc([
            'withdraw' => [
            ],
        ]);

        $transactionFactory = $this->container->get('TransactionFactory');
        $transaction = $transactionFactory->createFromArray(['2014-12-31', '4', 'private', 'withdraw', '1200', 'EUR']);

        $calc->calc($transaction);
    }

    public function testConfigured()
    {
        $withdrawPrivate = $this->createStub(CommissionConstant::class);
        $withdrawPrivate->method('calc')
            ->willReturn('10.00');

        $withdrawBussiness = $this->createStub(CommissionConstant::class);
        $withdrawBussiness->method('calc')
            ->willReturn('20.00');

        $depositPrivate = $this->createStub(CommissionConstant::class);
        $depositPrivate->method('calc')
            ->willReturn('30.00');

        $depositBussiness = $this->createStub(CommissionConstant::class);
        $depositBussiness->method('calc')
            ->willReturn('40.00');

        $calc = new CommissionCalc([
            'withdraw' => [
                'private' => $withdrawPrivate,
                'business' => $withdrawBussiness,
            ],
            'deposit' => [
                'private' => $depositPrivate,
                'business' => $depositBussiness,
            ],
        ]);

        $transactionFactory = $this->container->get('TransactionFactory');

        $transaction = $transactionFactory->createFromArray(['2014-12-31', '4', 'private', 'withdraw', '1200', 'EUR']);

        $this->assertEquals('10.00', $calc->calc($transactionFactory->createFromArray(['2014-12-31', '4', 'private', 'withdraw', '1200', 'EUR'])));
        $this->assertEquals('20.00', $calc->calc($transactionFactory->createFromArray(['2014-12-31', '4', 'business', 'withdraw', '1200', 'EUR'])));
        $this->assertEquals('30.00', $calc->calc($transactionFactory->createFromArray(['2014-12-31', '4', 'private', 'deposit', '1200', 'EUR'])));
        $this->assertEquals('40.00', $calc->calc($transactionFactory->createFromArray(['2014-12-31', '4', 'business', 'deposit', '1200', 'EUR'])));
    }
}
