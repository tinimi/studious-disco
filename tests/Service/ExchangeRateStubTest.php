<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\DTO\CurrencyDTO;
use App\Service\ExchangeRateStub;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

class ExchangeRateStubTest extends TestCase
{
    public function testGetByName()
    {
        $rate = new ExchangeRateStub(
            [
                'EUR' => [
                    'USD' => '1.1497',
                    'JPY' => '129.53',
                ],
            ]
        );

        $from = new CurrencyDTO('EUR', 2);
        $to = new CurrencyDTO('USD', 2);

        $this->assertEquals('1', $rate->getRatio(new DateTimeImmutable(), $from, $from));
        $this->assertEquals('1.1497', $rate->getRatio(new DateTimeImmutable(), $from, $to));
        $this->assertEquals('0.8697921196', $rate->getRatio(new DateTimeImmutable(), $to, $from));
    }

    public function testException()
    {
        $this->expectException(Exception::class);

        $rate = new ExchangeRateStub(
            [
                'EUR' => [
                    'USD' => '1.1497',
                    'JPY' => '129.53',
                ],
            ]
        );

        $from = new CurrencyDTO('EUR', 2);
        $to = new CurrencyDTO('CHN', 2);

        $rate->getRatio(new DateTimeImmutable(), $from, $to);
    }
}
