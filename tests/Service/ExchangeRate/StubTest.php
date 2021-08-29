<?php

declare(strict_types=1);

namespace App\Tests\Service\ExchangeRate;

use App\DTO\CurrencyDTO;
use App\Exceptions\CurrencyNotFoundException;
use App\Exceptions\RateException;
use App\Exceptions\RateInvalidException;
use App\Exceptions\RateNotFoundException;
use App\Service\ExchangeRate\Stub;
use App\Service\Math;
use App\Tests\AbstractMyTestCase;
use DateTimeImmutable;

class StubTest extends AbstractMyTestCase
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
            new Math(),
            $this->getCurrencyRepository()
        );

        $from = new CurrencyDTO('EUR', 2);
        $to = new CurrencyDTO('USD', 2);

        $this->assertEquals('1', $rate->getRatio(new DateTimeImmutable(), $from, $from));
        $this->assertEquals('1.1497', $rate->getRatio(new DateTimeImmutable(), $from, $to));
        $this->assertEquals('0.8697921197', $rate->getRatio(new DateTimeImmutable(), $to, $from));
    }

    public function testRateNotFoundException(): void
    {
        $this->expectException(RateNotFoundException::class);

        $rate = new Stub(
            [
                'EUR' => [
                    'USD' => '1.1497',
                    'JPY' => '129.53',
                ],
            ],
            new Math(),
            $this->getCurrencyRepository()
        );

        $from = new CurrencyDTO('EUR', 2);
        $to = new CurrencyDTO('CHN', 2);

        $rate->getRatio(new DateTimeImmutable(), $from, $to);
    }

    public function testRateException(): void
    {
        $this->expectException(RateException::class);

        $rate = new Stub(
            [ // @phpstan-ignore-line
                'EUR' => 'qwe',
            ],
            new Math(),
            $this->getCurrencyRepository()
        );
    }

    /**
     * @dataProvider providerRateInvalidException
     */
    public function testRateInvalidException(string $invalid): void
    {
        $this->expectException(RateInvalidException::class);

        $rate = new Stub(
            [
                'EUR' => [
                    'USD' => '1.1497',
                    'JPY' => $invalid,
                ],
            ],
            new Math(),
            $this->getCurrencyRepository()
        );
    }

    /**
     * @return array<array>
     */
    public function providerRateInvalidException(): array
    {
        return [
            ['q'],
            ['0'],
            [''],
            ['123.321.123'],
        ];
    }

    public function testCurrencyNotFoundException(): void
    {
        $this->expectException(CurrencyNotFoundException::class);

        $rate = new Stub(
            [
                'EUR' => [
                    'ZZZ' => '1.1497',
                    'JPY' => '123',
                ],
            ],
            new Math(),
            $this->getCurrencyRepository()
        );
    }
}
