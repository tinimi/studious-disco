<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\UserTypeDTO;

class UserTypeFactory implements UserTypeFactoryInterface
{
    protected $userTypes;

    public function getByName(string $name): UserTypeDTO
    {
        if (!isset($this->userTypes[$name])) {
            $this->userTypes[$name] = new UserTypeDTO($name);
        }

        return $this->userTypes[$name];
    }
}
