<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\Reader\CSVReader;
use App\DTO\TransactionDTO;
use App\Tests\AbstractMyTestCase;
use Exception;

class CSVReaderTest extends AbstractMyTestCase
{
    public function testException()
    {
        $this->expectException(Exception::class);

        $transactionFactory = $this->container->get('TransactionFactory');
        new CSVReader($transactionFactory, 'qwe');
    }

    public function testOk()
    {
        file_put_contents('/tmp/qwe', '2014-12-31,4,private,withdraw,1200.00,EUR');
        
        $transactionFactory = $this->container->get('TransactionFactory');
        $reader = new CSVReader($transactionFactory, '/tmp/qwe');
        $transaction = $reader->getTransaction();

        $this->assertInstanceOf(TransactionDTO::class, $transaction);
        $transaction = $reader->getTransaction();
        $this->assertNull($transaction);
    }
}