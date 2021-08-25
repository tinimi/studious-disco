<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\DTO\CurrencyDTO;
use App\Factory\CurrencyFactory;
use Exception;
use PHPUnit\Framework\TestCase;

class CurrencyFactoryTest extends TestCase
{
    public function testGetByName()
    {
        $factory = new CurrencyFactory([
            [
                'name' => 'USD',
                'scale' => 3,
            ],
        ]);

        $currency = $factory->getByName('USD');

        $this->assertInstanceOf(CurrencyDTO::class, $currency);
        $this->assertEquals('USD', $currency->getName());
        $this->assertEquals(3, $currency->getScale());
    }

    public function testGetByNameException()
    {
        $this->expectException(Exception::class);
        $factory = new CurrencyFactory([
            [
                'name' => 'USD',
                'scale' => 3,
            ],
        ]);

        $currency = $factory->getByName('USD2');
    }
}
