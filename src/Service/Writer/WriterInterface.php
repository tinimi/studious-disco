<?php

declare(strict_types=1);

namespace App\Service\Writer;

interface WriterInterface
{
    public function write(string $s): void;
}
