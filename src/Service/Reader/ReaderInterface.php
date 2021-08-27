<?php

declare(strict_types=1);

namespace App\Service\Reader;

use App\DTO\TransactionDTO;
use Generator;

interface ReaderInterface
{
    /**
     * @return Generator<TransactionDTO>
     */
    public function getTransaction(): Generator;
}
