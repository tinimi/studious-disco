<?php

declare(strict_types=1);

namespace App\Tests\DTO;

use App\DTO\OrderDTO;
use App\Tests\AbstractMyTestCase;

class OrderDTOTest extends AbstractMyTestCase
{
    public function testGetName(): void
    {
        $transaction = $this->createTransactionFromArray(['2014-12-31', '4', 'private', 'withdraw', '1200', 'EUR']);

        $order = new OrderDTO(10, $transaction);
        $this->assertEquals(10, $order->getLine());
        $this->assertSame($transaction, $order->getTransaction());

        $order->setCommission('10.00');

        $this->assertEquals('10.00', $order->getCommission());
    }
}
