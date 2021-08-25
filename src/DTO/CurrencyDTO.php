<?php

declare(strict_types=1);

namespace App\DTO;

class CurrencyDTO
{
    protected $name;
    protected $scale;

    public function __construct(string $name, int $scale)
    {
        $this->name = $name;
        $this->scale = $scale;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getScale(): int
    {
        return $this->scale;
    }
}
