<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\OperationTypeDTO;

class OperationTypeFactory implements OperationTypeFactoryInterface
{
    /**
     * @var array<OperationTypeDTO>
     */
    protected array $operationTypes;

    public function getByName(string $name): OperationTypeDTO
    {
        if (!isset($this->operationTypes[$name])) {
            $this->operationTypes[$name] = new OperationTypeDTO($name);
        }

        return $this->operationTypes[$name];
    }
}
