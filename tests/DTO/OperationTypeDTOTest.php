<?php

declare(strict_types=1);

namespace App\Tests\DTO;

use App\DTO\OperationTypeDTO;
use Exception;
use PHPUnit\Framework\TestCase;

class OperationTypeDTOTest extends TestCase
{
    /**
     * @dataProvider getTypes
     */
    public function testGetName(string $name): void
    {
        $type = new OperationTypeDTO($name);
        $this->assertEquals($name, $type->getName());
    }

    /**
     * @return array<int, array<int, string>>
     */
    public function getTypes(): array
    {
        return [
            ['deposit'],
            ['withdraw'],
        ];
    }

    public function testException(): void
    {
        $this->expectException(Exception::class);
        $t = new OperationTypeDTO('deposit2');
    }
}
