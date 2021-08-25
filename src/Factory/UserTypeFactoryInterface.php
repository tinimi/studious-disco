<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\UserTypeDTO;

interface UserTypeFactoryInterface
{
    public function getByName(string $name): UserTypeDTO;
}
