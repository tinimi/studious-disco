<?php

declare(strict_types=1);

namespace App\Service\Reader;

use App\DTO\TransactionDTO;

interface ReaderInterface
{
    public function getTransaction(): ?TransactionDTO;
}
