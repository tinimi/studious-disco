<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Factory\TransactionFactoryInterface;
use App\Service\Commission\Constant;
use App\Service\Math;
use App\Tests\AbstractMyTestCase;

class ConstantTest extends AbstractMyTestCase
{
    /**
     * @return array<array>
     */
    public function provider(): array
    {
        return [
            ['0', '1200.00', '0.00'],
            ['1', '1200.00', '12.00'],
            ['0.1', '1200.00', '1.20'],
            ['0.01', '1200.00', '0.12'],
            ['0.01', '1234.56', '0.12'],
            ['0.01', '1260.00', '0.13'],
        ];
    }

    /**
     * @dataProvider provider
     */
    public function testCommission(string $commission, string $amount, string $result): void
    {
        $commission = new Constant(new Math(), $commission);

        /**
         * @var TransactionFactoryInterface
         */
        $transactionFactory = $this->container->get('TransactionFactory');
        $this->assertNotNull($transactionFactory);
        $transaction = $transactionFactory->createFromArray(['2014-12-31', '4', 'private', 'withdraw', $amount, 'EUR']);

        $this->assertEquals($result, $commission->calc($transaction));
    }
}
