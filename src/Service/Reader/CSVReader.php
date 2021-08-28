<?php

declare(strict_types=1);

namespace App\Service\Reader;

use App\DTO\TransactionDTO;
use App\Exceptions\FileNotFoundException;
use App\Repository\TransactionRepositoryInterface;
use Generator;

class CSVReader implements ReaderInterface
{
    protected TransactionRepositoryInterface $Repository;
    protected string $fileName;

    public function __construct(TransactionRepositoryInterface $Repository)
    {
        $this->Repository = $Repository;
    }

    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * @return Generator<TransactionDTO>
     */
    public function getTransaction(): Generator
    {
        $handle = @fopen($this->fileName, 'r');
        if (!$handle) {
            throw new FileNotFoundException('File not found');
        }

        while ($row = fgetcsv($handle)) {
            yield $this->Repository->createFromArray($row);
        }

        fclose($handle);
    }
}
