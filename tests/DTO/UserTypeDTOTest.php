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
    public function testGetName($name)
    {
        $type = new UserTypeDTO($name);
        $this->assertEquals($name, $type->getName());
    }

    public function getTypes()
    {
        return [
            ['private'],
            ['business'],
        ];
    }

    public function testException()
    {
        $this->expectException(Exception::class);
        $t = new UserTypeDTO('qwe');
    }
}
