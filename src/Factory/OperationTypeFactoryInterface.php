<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\OperationTypeDTO;

interface OperationTypeFactoryInterface
{
    public function getByName(string $name): OperationTypeDTO;
}
