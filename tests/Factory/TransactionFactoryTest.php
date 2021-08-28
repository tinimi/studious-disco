<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\DTO\CurrencyDTO;
use App\DTO\TransactionDTO;
use App\Factory\CurrencyFactory;
use App\Factory\TransactionFactory;
use App\Service\ExchangeRate\Stub;
use App\Service\Math;
use App\Tests\AbstractMyTestCase;

class TransactionFactoryTest extends AbstractMyTestCase
{
    public function testCreateFromArray(): void
    {
        $math = new Math();

        $currencyFactory = new CurrencyFactory([
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
        ], $math);

        $transactionFactory = new TransactionFactory($currencyFactory, $rate, $math);

        $transaction = $transactionFactory->createFromArray(['2014-12-31', '4', 'private', 'withdraw', '1200', 'EUR']);

        $this->assertInstanceOf(TransactionDTO::class, $transaction);
        $this->assertEquals('4', $transaction->getUid());

        $converted = $transactionFactory->convert($transaction, new CurrencyDTO('USD', 2));
        $this->assertEquals('1379.64', $converted->getAmount());
    }
}
