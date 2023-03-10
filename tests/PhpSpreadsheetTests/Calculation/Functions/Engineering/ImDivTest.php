<?php

namespace PhpOffice\PhpSpreadsheetTests\Calculation\Functions\Engineering;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;

class ImDivTest extends AllSetupTeardown
{
    /**
     * @dataProvider providerIMDIV
     *
     * @param mixed $expectedResult
     */
    public function testIMDIV($expectedResult, ...$args): void
    {
        $this->runComplexTestCase('IMDIV', $expectedResult, ...$args);
    }

    public function providerIMDIV(): array
    {
        return require 'tests/data/Calculation/Engineering/IMDIV.php';
    }

    /**
     * @dataProvider providerImDivArray
     */
    public function testImDivArray(array $expectedResult, string $dividend, string $divisor): void
    {
        $calculation = Calculation::getInstance();

        $formula = "=IMDIV({$dividend}, {$divisor})";
        $result = $calculation->_calculateFormulaValue($formula);
        self::assertEquals($expectedResult, $result);
    }

    public function providerImDivArray(): array
    {
        return [
            'matrix' => [
                [
                    ['-0.36206896551724+0.3448275862069i', '-1.25i', '-0.375-0.875i'],
                    ['-0.10344827586207+0.24137931034483i', '-0.5i', '-0.5i'],
                    ['0.24137931034483+0.10344827586207i', '0.5i', '0.5'],
                    ['0.5', '1.25i', '0.875+0.375i'],
                ],
                '{"-1-2.5i", "-2.5i", "1-2.5i"; "-1-i", "-i", "1-i"; "-1+i", "i", "1+1"; "-1+2.5i", "+2.5i", "1+2.5i"}',
                '{"-2+5i", 2, "2+2i"}',
            ],
        ];
    }
}
