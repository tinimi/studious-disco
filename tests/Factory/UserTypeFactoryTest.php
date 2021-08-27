<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\DTO\UserTypeDTO;
use App\Factory\UserTypeFactory;
use PHPUnit\Framework\TestCase;

class UserTypeFactoryTest extends TestCase
{
    /**
     * @dataProvider getTypes
     */
    public function testGetByName(string $name): void
    {
        $factory = new UserTypeFactory();
        $type = $factory->getByName($name);

        $this->assertInstanceOf(UserTypeDTO::class, $type);
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
}
