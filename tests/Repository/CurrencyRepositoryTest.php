<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\DTO\CurrencyDTO;
use App\Exceptions\CurrencyNotFoundException;
use App\Repository\CurrencyRepository;
use PHPUnit\Framework\TestCase;

class CurrencyRepositoryTest extends TestCase
{
    public function testGetByName(): void
    {
        $Repository = new CurrencyRepository([
            [
                'name' => 'USD',
                'scale' => 3,
            ],
        ]);

        $currency = $Repository->getByName('USD');

        $this->assertInstanceOf(CurrencyDTO::class, $currency);
        $this->assertEquals('USD', $currency->getName());
        $this->assertEquals(3, $currency->getScale());
    }

    public function testGetByNameException(): void
    {
        $this->expectException(CurrencyNotFoundException::class);
        $Repository = new CurrencyRepository([
            [
                'name' => 'USD',
                'scale' => 3,
            ],
        ]);

        $currency = $Repository->getByName('USD2');
    }
}
