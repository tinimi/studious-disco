<?php

declare(strict_types=1);

namespace App\Tests\Service\ExchangeRate;

use App\DTO\CurrencyDTO;
use App\Exceptions\CurrencyNotFoundException;
use App\Exceptions\RateException;
use App\Service\ExchangeRate\Api;
use App\Service\Math;
use App\Tests\AbstractMyTestCase;
use BenMajor\ExchangeRatesAPI\ExchangeRatesAPI;
use DateTimeImmutable;
use stdClass;

class ApiTest extends AbstractMyTestCase
{
    public function getApi(bool $isPaid, float $response, string $baseCurrency = 'EUR'): Api
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

        return new Api($stub, $isPaid, new Math(), $baseCurrency, $this->getCurrencyRepository());
    }

    public function testSame(): void
    {
        $api = $this->getApi(false, 1.5);

        $from = new CurrencyDTO('QWE', 2);
        $this->assertEquals('1', $api->getRatio(new DateTimeImmutable(), $from, $from));
    }

    public function testException(): void
    {
        $this->expectException(RateException::class);

        $api = $this->getApi(false, 1.5);

        $from = new CurrencyDTO('QWE', 2);
        $to = new CurrencyDTO('ASD', 2);

        $api->getRatio(new DateTimeImmutable(), $from, $to);
    }

    public function testException2(): void
    {
        $this->expectException(CurrencyNotFoundException::class);

        $api = $this->getApi(false, 1.5, 'qwe');
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
