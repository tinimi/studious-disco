<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\TransactionStore;
use App\Tests\AbstractMyTestCase;

class TransactionStoreTest extends AbstractMyTestCase
{
    public function testGetByName(): void
    {
        $store = new TransactionStore();

        $transaction1 = $this->createTransactionFromArray(['2014-12-31', '4', 'private', 'withdraw', '1200', 'EUR']);
        $transaction2 = $this->createTransactionFromArray(['2014-12-30', '4', 'private', 'withdraw', '1200', 'EUR']);
        $transaction3 = $this->createTransactionFromArray(['2014-12-30', '5', 'private', 'withdraw', '1200', 'EUR']);
        $transaction4 = $this->createTransactionFromArray(['2015-12-31', '4', 'private', 'withdraw', '1200', 'EUR']);

        $store->store($transaction1);
        $store->store($transaction2);
        $store->store($transaction3);
        $store->store($transaction4);

        $transactions = $store->getTransactionsByWeek($transaction1->getDate(), $transaction1->getUid());

        $this->assertIsArray($transactions);
        $this->assertEquals(2, count($transactions));
        $this->assertSame($transactions[0], $transaction1);
        $this->assertSame($transactions[1], $transaction2);
    }
}
