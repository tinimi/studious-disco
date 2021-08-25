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
    public function testGetName($name)
    {
        $type = new OperationTypeDTO($name);
        $this->assertEquals($name, $type->getName());
    }

    public function getTypes()
    {
        return [
            ['deposit'],
            ['withdraw'],
        ];
    }

    public function testException()
    {
        $this->expectException(Exception::class);
        $t = new OperationTypeDTO('deposit2');
    }
}
