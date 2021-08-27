<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\DTO\CurrencyDTO;
use App\DTO\TransactionDTO;
use App\Factory\TransactionFactoryInterface;
use App\Tests\AbstractMyTestCase;

class TransactionFactoryTest extends AbstractMyTestCase
{
    public function testCreateFromArray(): void
    {
        /**
         * @var TransactionFactoryInterface
         */
        $transactionFactory = $this->container->get('TransactionFactory');
        $this->assertNotNull($transactionFactory);

        $transaction = $transactionFactory->createFromArray(['2014-12-31', '4', 'private', 'withdraw', '1200', 'EUR']);

        $this->assertInstanceOf(TransactionDTO::class, $transaction);
        $this->assertEquals('4', $transaction->getUid());

        $converted = $transactionFactory->convert($transaction, new CurrencyDTO('USD', 2));
        $this->assertEquals('1379.64', $converted->getAmount());
    }
}
