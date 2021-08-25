<?php

declare(strict_types=1);

namespace App\DTO;

use Exception;

class UserTypeDTO
{
    public const TYPE_PRIVATE = 'private';
    public const TYPE_BUSSINESS = 'business';

    protected bool $business;

    public function __construct(string $name)
    {
        switch ($name) {
            case self::TYPE_PRIVATE:
                $this->business = false;
                break;
            case self::TYPE_BUSSINESS:
                $this->business = true;
                break;
            default:
                throw new Exception('Invalid user type');
        }
    }

    public function getName(): string
    {
        return $this->business ? self::TYPE_BUSSINESS : self::TYPE_PRIVATE;
    }
}
