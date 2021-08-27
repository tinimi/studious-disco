<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\Math;
use Closure;
use PHPUnit\Framework\TestCase;

class MathTest extends TestCase
{
    protected Math $math;

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
    public function testAdd(string $leftOperand, string $rightOperand, int $scale, string $expectation): void
    {
        $this->assertEquals(
            $expectation,
            $this->math->add($leftOperand, $rightOperand, $scale)
        );
    }

    /**
     * @return array<array>
     */
    public function dataProviderForAddTesting(): array
    {
        return [
            'add 2 natural numbers' => ['1', '2', 2, '3.00'],
            'add negative number to a positive' => ['-1', '2', 2, '1.00'],
            'add natural number to a float' => ['1', '1.05123', 2, '2.05'],
        ];
    }

    /**
     * @dataProvider dataProviderForSubTesting
     */
    public function testSub(string $leftOperand, string $rightOperand, int $scale, string $expectation): void
    {
        $this->assertEquals(
            $expectation,
            $this->math->sub($leftOperand, $rightOperand, $scale)
        );
    }

    /**
     * @return array<array>
     */
    public function dataProviderForSubTesting(): array
    {
        return [
            ['2', '1', 2, '1.00'],
            ['3.3', '1.1', 2, '2.20'],
            ['3', '1.05123', 2, '1.95'],
        ];
    }

    /**
     * @dataProvider dataProviderForCompTesting
     */
    public function testComp(string $leftOperand, string $rightOperand, int $scale, int $expectation): void
    {
        $this->assertEquals(
            $expectation,
            $this->math->comp($leftOperand, $rightOperand, $scale)
        );
    }

    /**
     * @return array<array>
     */
    public function dataProviderForCompTesting(): array
    {
        return [
            ['1', '1', 2, 0],
            ['1.335', '1.333', 2, 1],
            ['1', '2', 2, -1],
        ];
    }

    /**
     * @dataProvider dataProviderForDivTesting
     */
    public function testDiv(string $leftOperand, string $rightOperand, int $scale, string $expectation): void
    {
        $this->assertEquals(
            $expectation,
            $this->math->div($leftOperand, $rightOperand, $scale)
        );
    }

    /**
     * @return array<array>
     */
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
    public function testMul(string $leftOperand, string $rightOperand, int $scale, string $expectation): void
    {
        $this->assertEquals(
            $expectation,
            $this->math->mul($leftOperand, $rightOperand, $scale)
        );
    }

    /**
     * @return array<array>
     */
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
    public function testRound(string $number, int $precision, string $expectation): void
    {
        $that = $this;
        Closure::bind(function () use ($that, $number, $precision, $expectation) {
            $that->assertEquals($expectation, $this->round($number, $precision)); // @phpstan-ignore-line
        }, $this->math, get_class($this->math))();
    }

    /**
     * @return array<array>
     */
    public function dataProviderForRound(): array
    {
        return [
            ['1', 2, '1'],
            ['1.555', 2, '1.56'],
            ['1.554', 2, '1.55'],
        ];
    }

    /**
     * @dataProvider dataProviderForGetZero
     */
    public function testgetZero(int $scale, string $expectation): void
    {
        $this->assertEquals(
            $expectation,
            $this->math->getZero($scale)
        );
    }

    /**
     * @return array<array>
     */
    public function dataProviderForGetZero()
    {
        return [
            [0, '0'],
            [1, '0.0'],
            [2, '0.00'],
            [3, '0.000'],
        ];
    }
}
