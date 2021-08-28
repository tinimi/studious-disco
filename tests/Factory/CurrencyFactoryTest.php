<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\DTO\CurrencyDTO;
use App\Exceptions\CurrencyNotFoundException;
use App\Factory\CurrencyFactory;
use PHPUnit\Framework\TestCase;

class CurrencyFactoryTest extends TestCase
{
    public function testGetByName(): void
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

    public function testGetByNameException(): void
    {
        $this->expectException(CurrencyNotFoundException::class);
        $factory = new CurrencyFactory([
            [
                'name' => 'USD',
                'scale' => 3,
            ],
        ]);

        $currency = $factory->getByName('USD2');
    }
}
