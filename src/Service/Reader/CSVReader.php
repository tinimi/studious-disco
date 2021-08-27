<?php

declare(strict_types=1);

namespace App\Service\Reader;

use App\DTO\TransactionDTO;
use App\Factory\TransactionFactoryInterface;
use Generator;

class CSVReader implements ReaderInterface
{
    protected TransactionFactoryInterface $factory;
    protected string $fileName;

    public function __construct(TransactionFactoryInterface $factory, string $fileName)
    {
        $this->factory = $factory;
        $this->fileName = $fileName;
    }

    /**
     * @return Generator<TransactionDTO>
     */
    public function getTransaction(): Generator
    {
        $handle = @fopen($this->fileName, 'r');
        if (!$handle) {
            throw new \Exception('File not found');
        }

        while ($row = fgetcsv($handle)) {
            yield $this->factory->createFromArray($row);
        }

        fclose($handle);
    }
}
