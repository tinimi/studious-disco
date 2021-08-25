<?php

declare(strict_types=1);

namespace App\Tests\Service\Writer;

use App\Service\Writer\EchoWriter;
use PHPUnit\Framework\TestCase;

class EchoWriterTest extends TestCase
{
    public function testWrite()
    {
        $writer = new EchoWriter();
        $writer->write('test');
        $this->expectOutputString("test\n");
    }
}
