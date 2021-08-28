<?php

declare(strict_types=1);

namespace App\Tests\Service\ExchangeRate;

use App\DTO\CurrencyDTO;
use App\Service\ExchangeRate\CacheMemory;
use App\Service\ExchangeRate\ExchangeRateInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class CacheMemoryTest extends TestCase
{
    public function testOnce(): void
    {
        $stub = $this->createStub(ExchangeRateInterface::class);
        /* @phpstan-ignore-next-line */
        $stub->expects($this->once())
            ->method('getRatio')
            ->willReturnOnConsecutiveCalls('1.23', '2.00', '3,00');

        $cache = new CacheMemory($stub);

        $from = new CurrencyDTO('EUR', 2);
        $to = new CurrencyDTO('USD', 2);
        $date = new DateTimeImmutable();

        $this->assertEquals('1.23', $cache->getRatio($date, $from, $to));
        $this->assertEquals('1.23', $cache->getRatio($date, $from, $to));
        $this->assertEquals('1.23', $cache->getRatio($date, $from, $to));
    }
}
