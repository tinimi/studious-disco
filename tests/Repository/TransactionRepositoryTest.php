<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\DTO\CurrencyDTO;
use App\DTO\TransactionDTO;
use App\Repository\CurrencyRepository;
use App\Repository\TransactionRepository;
use App\Service\ExchangeRate\Stub;
use App\Service\Math;
use App\Tests\AbstractMyTestCase;

class TransactionRepositoryTest extends AbstractMyTestCase
{
    public function testCreateFromArray(): void
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
        ], $math);

        $transactionRepository = new TransactionRepository($currencyRepository, $rate, $math);

        $transaction = $transactionRepository->createFromArray(['2014-12-31', '4', 'private', 'withdraw', '1200', 'EUR']);

        $this->assertInstanceOf(TransactionDTO::class, $transaction);
        $this->assertEquals('4', $transaction->getUid());

        $converted = $transactionRepository->convert($transaction, new CurrencyDTO('USD', 2));
        $this->assertEquals('1379.64', $converted->getAmount());
    }
}