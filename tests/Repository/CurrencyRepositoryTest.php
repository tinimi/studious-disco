<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\DTO\CurrencyDTO;
use App\Exceptions\CurrencyNotFoundException;
use App\Exceptions\InvalidCurrencyFormatException;
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

    public function testException1(): void
    {
        $this->expectException(InvalidCurrencyFormatException::class);
        $Repository = new CurrencyRepository([
            [
                'qwe' => 'USD',
                'scale' => 3,
            ],
        ]);
    }

    public function testException2(): void
    {
        $this->expectException(InvalidCurrencyFormatException::class);
        $Repository = new CurrencyRepository([
            [
                'name' => 'USD',
                'asd' => 3,
            ],
        ]);
    }

    public function testException3(): void
    {
        $this->expectException(InvalidCurrencyFormatException::class);
        $Repository = new CurrencyRepository([
            [
                'name' => 'USD1',
                'scale' => 3,
            ],
        ]);
    }

    public function testException4(): void
    {
        $this->expectException(InvalidCurrencyFormatException::class);
        $Repository = new CurrencyRepository([
            [
                'name' => 'USD',
                'scale' => -1,
            ],
        ]);
    }
}
