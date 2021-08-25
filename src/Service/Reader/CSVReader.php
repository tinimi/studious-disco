<?php

declare(strict_types=1);

namespace App\Service\Reader;

use App\DTO\TransactionDTO;
use App\Factory\TransactionFactoryInterface;

class CSVReader implements ReaderInterface
{
    protected $handle;
    protected $factory;

    public function __construct(TransactionFactoryInterface $factory, string $fileName = 'test.csv')
    {
        $this->factory = $factory;

        $this->handle = fopen($fileName, 'r');
        if (!$this->handle) {
            throw new \Exception('File not found');
        }
    }

    public function getTransaction(): ?TransactionDTO
    {
        $row = fgetcsv($this->handle);
        if ($row) {
            return $this->factory->createFromArray($row);
        }

        return null;
    }
}
