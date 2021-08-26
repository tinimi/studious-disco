<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\DTO\TransactionDTO;
use App\Service\Reader\CSVReader;
use App\Tests\AbstractMyTestCase;
use Exception;

class CSVReaderTest extends AbstractMyTestCase
{
    public function testException()
    {
        $this->expectException(Exception::class);

        $transactionFactory = $this->container->get('TransactionFactory');
        $reader = new CSVReader($transactionFactory, 'qwe');
        foreach ($reader->getTransaction() as $t) {
        }
    }

    public function testOk()
    {
        file_put_contents('/tmp/qwe', '2014-12-31,4,private,withdraw,1200.00,EUR');

        $transactionFactory = $this->container->get('TransactionFactory');
        $reader = new CSVReader($transactionFactory, '/tmp/qwe');
        foreach ($reader->getTransaction() as $transaction) {
            $this->assertInstanceOf(TransactionDTO::class, $transaction);
        }
    }
}
