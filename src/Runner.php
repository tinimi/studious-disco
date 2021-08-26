<?php

declare(strict_types=1);

namespace App;

use App\DTO\OrderDTO;
use App\Service\CommissionCalcInterface;
use App\Service\Reader\ReaderInterface;
use App\Service\Writer\WriterInterface;

class Runner
{
    protected ReaderInterface $reader;
    protected WriterInterface $writer;
    protected CommissionCalcInterface $commissionCalc;
    protected $sort;

    public function __construct(ReaderInterface $reader, WriterInterface $writer, CommissionCalcInterface $commissionCalc, bool $sort)
    {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->commissionCalc = $commissionCalc;
        $this->sort = $sort;
    }

    public function run(): void
    {
        if ($this->sort) {
            $this->runWithSort();
        } else {
            $this->runWithoutSort();
        }
    }

    public function runWithoutSort(): void
    {
        foreach ($this->reader->getTransaction() as $t) {
            $this->writer->write($this->commissionCalc->calc($t));
        }
    }

    public function runWithSort(): void
    {
        $data = [];
        $i = 0;
        foreach ($this->reader->getTransaction() as $t) {
            $data[] = new OrderDTO(++$i, $t);
        }
        usort($data, function ($a, $b) {
            return $a->getTransaction()->getDate()->format('Y-m-d') <=> $b->getTransaction()->getDate()->format('Y-m-d');
        });

        foreach ($data as $row) {
            $row->setCommission($this->commissionCalc->calc($row->getTransaction()));
        }

        usort($data, function ($a, $b) {
            return $a->getLine() <=> $b->getLine();
        });

        foreach ($data as $row) {
            $this->writer->write($row->getCommission());
        }
    }
}
