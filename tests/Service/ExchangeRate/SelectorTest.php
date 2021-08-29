<?php

declare(strict_types=1);

namespace App\Tests\Service\ExchangeRate;

use App\DTO\CurrencyDTO;
use App\Exceptions\RateSelectorException;
use App\Service\ExchangeRate\ExchangeRateInterface;
use App\Service\ExchangeRate\Selector;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class SelectorTest extends TestCase
{
    public function testOnce(): void
    {
        $stub1 = $this->createStub(ExchangeRateInterface::class);

        $stub1->method('getRatio')
            ->willReturn('1.00');

        $stub2 = $this->createStub(ExchangeRateInterface::class);
        /* @phpstan-ignore-next-line */
        $stub2->expects($this->once())
            ->method('getRatio')
            ->willReturn('2.00');

        $selector = new Selector('asd', ['qwe' => $stub1, 'asd' => $stub2]);

        $from = new CurrencyDTO('EUR', 2);
        $to = new CurrencyDTO('USD', 2);
        $date = new DateTimeImmutable();

        $this->assertEquals('2.00', $selector->getRatio($date, $from, $to));
    }

    public function testException(): void
    {
        $this->expectException(RateSelectorException::class);

        $stub1 = $this->createStub(ExchangeRateInterface::class);

        $stub1->method('getRatio')
            ->willReturn('1.00');

        $stub2 = $this->createStub(ExchangeRateInterface::class);

        $stub2->method('getRatio')
            ->willReturn('2.00');

        $selector = new Selector('zxc', ['qwe' => $stub1, 'asd' => $stub2]);
    }
}
