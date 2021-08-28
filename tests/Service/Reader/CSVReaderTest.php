<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\DTO\CurrencyDTO;
use App\DTO\TransactionDTO;
use App\Exceptions\FileNotFoundException;
use App\Factory\TransactionFactoryInterface;
use App\Service\Reader\CSVReader;
use DateTimeImmutable;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class CSVReaderTest extends TestCase
{
    public function testException(): void
    {
        $this->expectException(FileNotFoundException::class);

        $transaction = new TransactionDTO(
            $date = new DateTimeImmutable('2020-01-02'),
            '123',
            'private',
            'deposit',
            '1200',
            $currency = new CurrencyDTO('USD', 2)
        );

        $transactionFactory = $this->createStub(TransactionFactoryInterface::class);
        $transactionFactory->method('createFromArray')->willReturn($transaction);

        $reader = new CSVReader($transactionFactory);
        $reader->setFileName('/tmp/qwe/asdf/zxcv');
        foreach ($reader->getTransaction() as $t) {
        }
    }

    public function testOk(): void
    {
        $structure = [
            'input.csv' => '2014-12-31,4,private,withdraw,1200.00,EUR',
        ];

        $root = vfsStream::setup('root', null, $structure);

        $transaction = new TransactionDTO(
            $date = new DateTimeImmutable('2020-01-02'),
            '123',
            'private',
            'deposit',
            '1200',
            $currency = new CurrencyDTO('USD', 2)
        );

        $transactionFactory = $this->createStub(TransactionFactoryInterface::class);
        $transactionFactory->method('createFromArray')->willReturn($transaction);

        $reader = new CSVReader($transactionFactory);
        $reader->setFileName($root->url().'/input.csv');
        foreach ($reader->getTransaction() as $transaction) {
            $this->assertInstanceOf(TransactionDTO::class, $transaction);
        }
    }
}
