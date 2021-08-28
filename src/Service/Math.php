<?php

declare(strict_types=1);

namespace App\Service;

use ValueError;

class Math
{
    public function add(string $leftOperand, string $rightOperand, int $scale): string
    {
        return $this->round(bcadd($leftOperand, $rightOperand, $scale + 1), $scale);
    }

    public function sub(string $num1, string $num2, int $scale): string
    {
        return $this->round(bcsub($num1, $num2, $scale + 1), $scale);
    }

    public function div(string $num1, string $num2, int $scale): string
    {
        $result = bcdiv($num1, $num2, $scale + 1);
        /*
         * This is trick for phpstan only. Generally bcdiv throws fatal error in case of division by zero
         */
        $result = $result ?? '0';

        return $this->round($result, $scale);
    }

    public function mul(string $num1, string $num2, int $scale): string
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

    public function comp(string $num1, string $num2, int $scale): int
    {
        return bccomp($this->round($num1, $scale), $this->round($num2, $scale), $scale);
    }

    public function isWellFormed(string $num): bool
    {
        try {
            bcadd($num, '1', 2); // @phpstan-ignore-line

            return true;
        } catch (ValueError $e) {
            return false;
        }
    }

    protected function round(string $number, int $precision): string
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
