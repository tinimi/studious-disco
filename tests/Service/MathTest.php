<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\Math;
use PHPUnit\Framework\TestCase;

class MathTest extends TestCase
{
    protected $math;

    public function setUp(): void
    {
        $this->math = new Math();
    }

    public function __tearDown(): void
    {
        unset($this->math);
    }

    /**
     * @dataProvider dataProviderForAddTesting
     */
    public function testAdd(string $leftOperand, string $rightOperand, int $scale, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->add($leftOperand, $rightOperand, $scale)
        );
    }

    public function dataProviderForAddTesting(): array
    {
        return [
            'add 2 natural numbers' => ['1', '2', 2, '3.00'],
            'add negative number to a positive' => ['-1', '2', 2, '1.00'],
            'add natural number to a float' => ['1', '1.05123', 2, '2.05'],
        ];
    }

    /**
     * @dataProvider dataProviderForDivTesting
     */
    public function testDiv(string $leftOperand, string $rightOperand, int $scale, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->div($leftOperand, $rightOperand, $scale)
        );
    }

    public function dataProviderForDivTesting(): array
    {
        return [
            ['1', '2', 2, '0.50'],
            ['-1', '2', 2, '-0.50'],
            ['1', '1.05123', 2, '0.95'],
            ['1', '1.045', 2, '0.96'],
            ['2', '3', 4, '0.6667'],
        ];
    }

    /**
     * @dataProvider dataProviderForMulTesting
     */
    public function testMul(string $leftOperand, string $rightOperand, int $scale, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->mul($leftOperand, $rightOperand, $scale)
        );
    }

    public function dataProviderForMulTesting(): array
    {
        return [
            ['1', '2', 2, '2.00'],
            ['-1', '2', 2, '-2.00'],
            ['1', '1.05123', 2, '1.05'],
            ['1', '1.045', 2, '1.05'],
            ['2', '3', 4, '6.0000'],
            ['0.1', '2.56', 2, '0.26'],
        ];
    }

    /**
     * @dataProvider dataProviderForRound
     */
    public function testRound($number, $precision, $expectation)
    {
        $that = $this;
        $assertPropertyClosure = function () use ($that, $number, $precision, $expectation) {
            $that->assertEquals($expectation, $this->round($number, $precision));
        };

        $doAssertPropertyClosure = $assertPropertyClosure->bindTo($this->math, get_class($this->math));

        $doAssertPropertyClosure();
    }

    public function dataProviderForRound(): array
    {
        return [
            ['1', 2, '1'],
            ['1.555', 2, '1.56'],
            ['1.554', 2, '1.55'],
        ];
    }
}
