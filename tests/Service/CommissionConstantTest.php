<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\CommissionConstant;
use App\Service\Math;
use App\Tests\AbstractMyTestCase;

class CommissionConstantTest extends AbstractMyTestCase
{
    public function provider()
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
    public function testCommission($commission, $amount, $result)
    {
        $commission = new CommissionConstant(new Math(), $commission);

        $transactionFactory = $this->container->get('TransactionFactory');
        $transaction = $transactionFactory->createFromArray(['2014-12-31', '4', 'private', 'withdraw', $amount, 'EUR']);

        $this->assertEquals($result, $commission->calc($transaction));
    }
}
