<?php

declare(strict_types=1);

namespace App\Service;

class Math
{
    public function add(string $leftOperand, string $rightOperand, int $scale): string
    {
        return bcadd($leftOperand, $rightOperand, $scale);
    }

    public function div(string $num1, string $num2, int $scale): string
    {
        return $this->round(bcdiv($num1, $num2, $scale + 1), $scale);
    }

    public function mul(string $num1, string $num2, ?int $scale = null): string
    {
        return $this->round(bcmul($num1, $num2, $scale + 1), $scale);
    }

    public function getZero(int $scale): string
    {
        if (0 === $scale) {
            return '0';
        }

        return '0.'.str_repeat('0', $scale);
    }

    protected function round($number, $precision = 0)
    {
        if (strpos($number, '.') !== false) {
            if ($number[0] !== '-') {
                return bcadd($number, '0.'.str_repeat('0', $precision).'5', $precision);
            }

            return bcsub($number, '0.'.str_repeat('0', $precision).'5', $precision);
        }

        return $number;
    }
}
