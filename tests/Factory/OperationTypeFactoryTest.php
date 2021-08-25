<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\DTO\OperationTypeDTO;
use App\Factory\OperationTypeFactory;
use PHPUnit\Framework\TestCase;

class OperationTypeFactoryTest extends TestCase
{
    /**
     * @dataProvider getTypes
     */
    public function testGetByName($name)
    {
        $factory = new OperationTypeFactory();
        $type = $factory->getByName($name);

        $this->assertInstanceOf(OperationTypeDTO::class, $type);
        $this->assertEquals($name, $type->getName());
    }

    public function getTypes()
    {
        return [
            ['deposit'],
            ['withdraw'],
        ];
    }
}
