<?php

declare(strict_types=1);

namespace App\Tests\DTO;

use App\DTO\CurrencyDTO;
use App\DTO\TransactionDTO;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class TransactionDTOTest extends TestCase
{
    public function testGetName(): void
    {
        $t = new TransactionDTO(
            $date = new DateTimeImmutable('2020-01-02'),
            '123',
            'private',
            'deposit',
            '1200',
            $currency = new CurrencyDTO('USD', 2)
        );

        $this->assertSame($date, $t->getDate());
        $this->assertEquals('123', $t->getUid());
        $this->assertEquals('private', $t->getUserType());
        $this->assertEquals('deposit', $t->getOperationType());
        $this->assertEquals('1200', $t->getAmount());
        $this->assertSame($currency, $t->getCurrency());
    }
}
