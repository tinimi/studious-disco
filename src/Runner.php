<?php

declare(strict_types=1);

namespace App;

use App\DTO\OrderDTO;
use App\Exceptions\AppException;
use App\Service\CommissionCalcInterface;
use App\Service\Reader\ReaderInterface;
use App\Service\Writer\WriterInterface;
use Psr\Log\LoggerInterface;

class Runner
{
    protected ReaderInterface $reader;
    protected WriterInterface $writer;
    protected CommissionCalcInterface $commissionCalc;
    protected bool $sort;
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger, ReaderInterface $reader, WriterInterface $writer, CommissionCalcInterface $commissionCalc, bool $sort)
    {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->commissionCalc = $commissionCalc;
        $this->sort = $sort;
        $this->logger = $logger;
    }

    /**
     * @param array<string> $argv
     */
    public function main(int $argc, array $argv): int
    {
        if ($argc !== 2) {
            $this->logger->error("Usage: {$argv[0]} filename.csv\n");

            return 1;
        }

        return $this->run($argv[1]);
    }

    public function run(string $fileName): int
    {
        $this->logger->notice('Run', [$this->sort]);
        try {
            if ($this->sort) {
                $this->runWithSort($fileName);
            } else {
                $this->runWithoutSort($fileName);
            }

            return 0;
        } catch (AppException $e) {
            $this->logger->error($e->getMessage());

            return 1;
        }
    }

    public function runWithoutSort(string $fileName): void
    {
        $this->reader->setFileName($fileName);
        foreach ($this->reader->getTransaction() as $t) {
            $this->writer->write($this->commissionCalc->calc($t));
        }
    }

    public function runWithSort(string $fileName): void
    {
        $this->reader->setFileName($fileName);
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
