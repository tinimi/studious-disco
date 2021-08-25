<?php

declare(strict_types=1);

namespace App\DTO;

use Exception;

class OperationTypeDTO
{
    public const TYPE_DEPOSIT = 'deposit';
    public const TYPE_WITHDRAW = 'withdraw';

    protected bool $deposit;

    public function __construct(string $name)
    {
        switch ($name) {
            case self::TYPE_DEPOSIT:
                $this->deposit = true;
                break;
            case self::TYPE_WITHDRAW:
                $this->deposit = false;
                break;
            default:
                throw new Exception('Invalid operation type');
        }
    }

    public function getName(): string
    {
        return $this->deposit ? self::TYPE_DEPOSIT : self::TYPE_WITHDRAW;
    }
}
