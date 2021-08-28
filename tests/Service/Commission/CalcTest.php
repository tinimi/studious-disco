<?php

declare(strict_types=1);

namespace App\Tests\Service\Commission;

use App\Exceptions\CommissionCalcException;
use App\Factory\TransactionFactoryInterface;
use App\Service\Commission\Calc;
use App\Service\Commission\Constant;
use App\Tests\AbstractMyTestCase;

class CalcTest extends AbstractMyTestCase
{
    public function testNotConfigured(): void
    {
        $this->expectException(CommissionCalcException::class);

        $calc = new Calc([]);

        /**
         * @var TransactionFactoryInterface
         */
        $transactionFactory = $this->container->get('TransactionFactory');
        $this->assertNotNull($transactionFactory);
        $transaction = $transactionFactory->createFromArray(['2014-12-31', '4', 'private', 'withdraw', '1200', 'EUR']);

        $calc->calc($transaction);
    }

    public function testNotConfigured2(): void
    {
        $this->expectException(CommissionCalcException::class);

        $calc = new Calc([
            'withdraw' => [
            ],
        ]);

        /**
         * @var TransactionFactoryInterface
         */
        $transactionFactory = $this->container->get('TransactionFactory');
        $this->assertNotNull($transactionFactory);
        $transaction = $transactionFactory->createFromArray(['2014-12-31', '4', 'private', 'withdraw', '1200', 'EUR']);

        $calc->calc($transaction);
    }

    public function testConfigured(): void
    {
        $withdrawPrivate = $this->createStub(Constant::class);
        $withdrawPrivate->method('calc')
            ->willReturn('10.00');

        $withdrawBussiness = $this->createStub(Constant::class);
        $withdrawBussiness->method('calc')
            ->willReturn('20.00');

        $depositPrivate = $this->createStub(Constant::class);
        $depositPrivate->method('calc')
            ->willReturn('30.00');

        $depositBussiness = $this->createStub(Constant::class);
        $depositBussiness->method('calc')
            ->willReturn('40.00');

        $calc = new Calc([
            'withdraw' => [
                'private' => $withdrawPrivate,
                'business' => $withdrawBussiness,
            ],
            'deposit' => [
                'private' => $depositPrivate,
                'business' => $depositBussiness,
            ],
        ]);

        /**
         * @var TransactionFactoryInterface
         */
        $transactionFactory = $this->container->get('TransactionFactory');
        $this->assertNotNull($transactionFactory);

        $transaction = $transactionFactory->createFromArray(['2014-12-31', '4', 'private', 'withdraw', '1200', 'EUR']);

        $this->assertEquals('10.00', $calc->calc($transactionFactory->createFromArray(['2014-12-31', '4', 'private', 'withdraw', '1200', 'EUR'])));
        $this->assertEquals('20.00', $calc->calc($transactionFactory->createFromArray(['2014-12-31', '4', 'business', 'withdraw', '1200', 'EUR'])));
        $this->assertEquals('30.00', $calc->calc($transactionFactory->createFromArray(['2014-12-31', '4', 'private', 'deposit', '1200', 'EUR'])));
        $this->assertEquals('40.00', $calc->calc($transactionFactory->createFromArray(['2014-12-31', '4', 'business', 'deposit', '1200', 'EUR'])));
    }
}
