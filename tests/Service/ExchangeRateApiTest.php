<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\DTO\CurrencyDTO;
use App\Service\ExchangeRateApi;
use App\Service\Math;
use BenMajor\ExchangeRatesAPI\ExchangeRatesAPI;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;
use stdClass;

class ExchangeRateApiTest extends TestCase
{
    public function getApi(bool $isPaid, float $response): ExchangeRateApi
    {
        $response = new stdClass();
        $response->rates = new stdClass();
        $response->rates->CHN = 1.5;

        $stub = $this->createStub(ExchangeRatesAPI::class);
        $stub->method('setFetchDate')
            ->willReturnSelf();
        $stub->method('setBaseCurrency')
            ->willReturnSelf();
        $stub->method('addRate')
            ->willReturnSelf();
        $stub->method('fetch')
            ->willReturn($response);

        return new ExchangeRateApi($stub, $isPaid, new Math());
    }

    public function testSame(): void
    {
        $api = $this->getApi(false, 1.5);

        $from = new CurrencyDTO('QWE', 2);
        $this->assertEquals('1', $api->getRatio(new DateTimeImmutable(), $from, $from));
    }

    public function testException(): void
    {
        $this->expectException(Exception::class);

        $api = $this->getApi(false, 1.5);

        $from = new CurrencyDTO('QWE', 2);
        $to = new CurrencyDTO('ASD', 2);

        $api->getRatio(new DateTimeImmutable(), $from, $to);
    }

    public function testNoException(): void
    {
        $api = $this->getApi(true, 1.5);

        $from = new CurrencyDTO('QWE', 2);
        $to = new CurrencyDTO('CHN', 2);

        $this->assertEquals('1.5', $api->getRatio(new DateTimeImmutable(), $from, $to));
    }

    public function testInverse(): void
    {
        $api = $this->getApi(false, 1.5);

        $from = new CurrencyDTO('CHN', 2);
        $to = new CurrencyDTO('EUR', 2);

        $this->assertEquals('0.6666666667', $api->getRatio(new DateTimeImmutable(), $from, $to));
    }

    public function testSuccess(): void
    {
        $api = $this->getApi(false, 1.5);

        $from = new CurrencyDTO('EUR', 2);
        $to = new CurrencyDTO('CHN', 2);

        $this->assertEquals('1.5', $api->getRatio(new DateTimeImmutable(), $from, $to));
    }
}
