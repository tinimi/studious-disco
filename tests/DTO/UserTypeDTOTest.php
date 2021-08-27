<?php

declare(strict_types=1);

namespace App\Tests\DTO;

use App\DTO\UserTypeDTO;
use Exception;
use PHPUnit\Framework\TestCase;

class UserTypeDTOTest extends TestCase
{
    /**
     * @dataProvider getTypes
     */
    public function testGetName(string $name): void
    {
        $type = new UserTypeDTO($name);
        $this->assertEquals($name, $type->getName());
    }

    /**
     * @return array<int, array<int, string>>
     */
    public function getTypes(): array
    {
        return [
            ['private'],
            ['business'],
        ];
    }

    public function testException(): void
    {
        $this->expectException(Exception::class);
        $t = new UserTypeDTO('qwe');
    }
}
