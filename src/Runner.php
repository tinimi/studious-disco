<?php

declare(strict_types=1);

namespace App;

use App\Service\ComissionCalcInterface;
use App\Service\Reader\ReaderInterface;
use App\Service\Writer\WriterInterface;

class Runner
{
    protected ReaderInterface $reader;
    protected WriterInterface $writer;
    protected ComissionCalcInterface $comissionCalc;

    public function __construct(ReaderInterface $reader, WriterInterface $writer, ComissionCalcInterface $comissionCalc)
    {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->comissionCalc = $comissionCalc;
    }

    public function run(): void
    {
        while ($t = $this->reader->getTransaction()) {
            $this->writer->write($this->comissionCalc->calc($t));
        }
    }
}
