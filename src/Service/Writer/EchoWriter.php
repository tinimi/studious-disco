<?php

declare(strict_types=1);

namespace App\Service\Writer;

class EchoWriter implements WriterInterface
{
    public function write(string $s): void
    {
        echo $s."\n";
    }
}
