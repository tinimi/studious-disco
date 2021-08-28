<?php

declare(strict_types=1);

namespace App\Tests\Service\ExchangeRate;

use App\DTO\CurrencyDTO;
use App\Service\ExchangeRate\Stub;
use App\Service\Math;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

class StubTest extends TestCase
{
    public function testGetByName(): void
    {
        $rate = new Stub(
            [
                'EUR' => [
                    'USD' => '1.1497',
                    'JPY' => '129.53',
                ],
            ],
            new Math()
        );

        $from = new CurrencyDTO('EUR', 2);
        $to = new CurrencyDTO('USD', 2);

        $this->assertEquals('1', $rate->getRatio(new DateTimeImmutable(), $from, $from));
        $this->assertEquals('1.1497', $rate->getRatio(new DateTimeImmutable(), $from, $to));
        $this->assertEquals('0.8697921197', $rate->getRatio(new DateTimeImmutable(), $to, $from));
    }

    public function testException(): void
    {
        $this->expectException(Exception::class);

        $rate = new Stub(
            [
                'EUR' => [
                    'USD' => '1.1497',
                    'JPY' => '129.53',
                ],
            ],
            new Math()
        );

        $from = new CurrencyDTO('EUR', 2);
        $to = new CurrencyDTO('CHN', 2);

        $rate->getRatio(new DateTimeImmutable(), $from, $to);
    }
}
