<?php

declare(strict_types=1);

namespace App\Tests\DTO;

use App\DTO\CurrencyDTO;
use App\DTO\OperationTypeDTO;
use App\DTO\TransactionDTO;
use App\DTO\UserTypeDTO;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class TransactionDTOTest extends TestCase
{
    public function testGetName(): void
    {
        $t = new TransactionDTO(
            $date = new DateTimeImmutable('2020-01-02'),
            '123',
            $userType = new UserTypeDTO('private'),
            $operationType = new OperationTypeDTO('deposit'),
            '1200',
            $currency = new CurrencyDTO('USD', 2)
        );

        $this->assertSame($date, $t->getDate());
        $this->assertEquals('123', $t->getUid());
        $this->assertSame($userType, $t->getUserType());
        $this->assertSame($operationType, $t->getOperationType());
        $this->assertEquals('1200', $t->getAmount());
        $this->assertSame($currency, $t->getCurrency());
    }
}
