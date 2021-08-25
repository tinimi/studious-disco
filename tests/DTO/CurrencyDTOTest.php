<?php

declare(strict_types=1);

namespace App\Tests\DTO;

use App\DTO\CurrencyDTO;
use PHPUnit\Framework\TestCase;

class CurrencyDTOTest extends TestCase
{
    public function testGetName()
    {
        $currency = new CurrencyDTO('USD', 3);
        $this->assertEquals('USD', $currency->getName());
    }

    public function testGetScale()
    {
        $currency = new CurrencyDTO('USD', 3);
        $this->assertEquals(3, $currency->getScale());
    }
}
