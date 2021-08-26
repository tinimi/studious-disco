<?php

declare(strict_types=1);

namespace App;

use App\Service\CommissionCalcInterface;
use App\Service\Reader\ReaderInterface;
use App\Service\Writer\WriterInterface;

class Runner
{
    protected ReaderInterface $reader;
    protected WriterInterface $writer;
    protected CommissionCalcInterface $commissionCalc;

    public function __construct(ReaderInterface $reader, WriterInterface $writer, CommissionCalcInterface $commissionCalc)
    {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->commissionCalc = $commissionCalc;
    }

    public function run(): void
    {
        while ($t = $this->reader->getTransaction()) {
            $this->writer->write($this->commissionCalc->calc($t));
        }
    }
}
