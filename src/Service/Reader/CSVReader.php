<?php

declare(strict_types=1);

namespace App\Service\Reader;

use App\Factory\TransactionFactoryInterface;

class CSVReader implements ReaderInterface
{
    protected $handle;
    protected $factory;
    protected $fileName;

    public function __construct(TransactionFactoryInterface $factory, string $fileName)
    {
        $this->factory = $factory;
        $this->fileName = $fileName;
    }

    public function getTransaction()
    {
        $this->handle = @fopen($this->fileName, 'r');
        if (!$this->handle) {
            throw new \Exception('File not found');
        }

        while ($row = fgetcsv($this->handle)) {
            yield $this->factory->createFromArray($row);
        }

        fclose($this->handle);
    }
}
