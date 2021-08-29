<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\DTO\CurrencyDTO;
use App\DTO\TransactionDTO;
use App\Exceptions\InvalidFormatException;
use App\Repository\TransactionRepository;
use App\Service\ExchangeRate\Stub;
use App\Service\Math;
use App\Tests\AbstractMyTestCase;

class TransactionRepositoryTest extends AbstractMyTestCase
{
    public function testCreateFromArray(): void
    {
        $math = new Math();

        $currencyRepository = $this->getCurrencyRepository();

        $rate = new Stub([
            'EUR' => [
                'USD' => '1.1497',
                'JPY' => '129.53',
            ],
        ], $math, $currencyRepository);

        $transactionRepository = new TransactionRepository($currencyRepository, $rate, $math);

        $transaction = $transactionRepository->createFromArray(['2014-12-31', '4', 'private', 'withdraw', '1200', 'EUR']);

        $this->assertInstanceOf(TransactionDTO::class, $transaction);
        $this->assertEquals('4', $transaction->getUid());

        $converted = $transactionRepository->convert($transaction, new CurrencyDTO('USD', 2));
        $this->assertEquals('1379.64', $converted->getAmount());
    }

    /**
     * @dataProvider providerException
     *
     * @param array<string> $row
     */
    public function testException1(array $row): void
    {
        $this->expectException(InvalidFormatException::class);
        $math = new Math();

        $currencyRepository = $this->getCurrencyRepository();

        $rate = new Stub([
            'EUR' => [
                'USD' => '1.1497',
                'JPY' => '129.53',
            ],
        ], $math, $currencyRepository);

        $transactionRepository = new TransactionRepository($currencyRepository, $rate, $math);

        $transaction = $transactionRepository->createFromArray($row);
    }

    /**
     * @return array<array>
     */
    public function providerException(): array
    {
        return [
            [['2014-12-31', '4', 'private', 'withdraw']],
            [['qwe2014-12-31', '4', 'private', 'withdraw', '1200', 'EUR']],
            [['2014-12-31', '4', 'private', 'withdraw', '1200q', 'EUR']],
        ];
    }
}
