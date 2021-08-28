<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\DTO\TransactionDTO;
use App\Factory\TransactionFactoryInterface;
use App\Service\Reader\CSVReader;
use App\Tests\AbstractMyTestCase;
use Exception;

class CSVReaderTest extends AbstractMyTestCase
{
    public function testException(): void
    {
        $this->expectException(Exception::class);

        /**
         * @var TransactionFactoryInterface
         */
        $transactionFactory = $this->container->get('TransactionFactory');
        $this->assertNotNull($transactionFactory);
        $reader = new CSVReader($transactionFactory);
        $reader->setFileName('/tmp/qwe');
        foreach ($reader->getTransaction() as $t) {
        }
    }

    public function testOk(): void
    {
        file_put_contents('/tmp/qwe', '2014-12-31,4,private,withdraw,1200.00,EUR');

        /**
         * @var TransactionFactoryInterface
         */
        $transactionFactory = $this->container->get('TransactionFactory');
        $this->assertNotNull($transactionFactory);
        $reader = new CSVReader($transactionFactory);
        $reader->setFileName('/tmp/qwe');
        foreach ($reader->getTransaction() as $transaction) {
            $this->assertInstanceOf(TransactionDTO::class, $transaction);
        }
    }
}
