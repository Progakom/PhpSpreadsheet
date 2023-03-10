<?php

namespace PhpOffice\PhpSpreadsheetTests\Calculation\Functions\Engineering;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Calculation\Exception as CalcExp;
use PhpOffice\PhpSpreadsheet\Calculation\Functions;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PHPUnit\Framework\TestCase;

class Hex2DecTest extends TestCase
{
    /**
     * @var string
     */
    private $compatibilityMode;

    protected function setUp(): void
    {
        $this->compatibilityMode = Functions::getCompatibilityMode();
    }

    protected function tearDown(): void
    {
        Functions::setCompatibilityMode($this->compatibilityMode);
    }

    /**
     * @dataProvider providerHEX2DEC
     *
     * @param mixed $expectedResult
     * @param mixed $formula
     */
    public function testHEX2DEC($expectedResult, $formula): void
    {
        if ($expectedResult === 'exception') {
            $this->expectException(CalcExp::class);
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A2', 'B');
        $sheet->getCell('A1')->setValue("=HEX2DEC($formula)");
        $result = $sheet->getCell('A1')->getCalculatedValue();
        self::assertEquals($expectedResult, $result);
        $spreadsheet->disconnectWorksheets();
    }

    public function providerHEX2DEC(): array
    {
        return require 'tests/data/Calculation/Engineering/HEX2DEC.php';
    }

    /**
     * @dataProvider providerHEX2DEC
     *
     * @param mixed $expectedResult
     * @param mixed $formula
     */
    public function testHEX2DECOds($expectedResult, $formula): void
    {
        Functions::setCompatibilityMode(Functions::COMPATIBILITY_OPENOFFICE);
        if ($expectedResult === 'exception') {
            $this->expectException(CalcExp::class);
        }
        if ($formula === 'true') {
            $expectedResult = 1;
        } elseif ($formula === 'false') {
            $expectedResult = 0;
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A2', 'B');
        $sheet->getCell('A1')->setValue("=HEX2DEC($formula)");
        $result = $sheet->getCell('A1')->getCalculatedValue();
        self::assertEquals($expectedResult, $result);
        $spreadsheet->disconnectWorksheets();
    }

    public function testHEX2DECFrac(): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        Functions::setCompatibilityMode(Functions::COMPATIBILITY_GNUMERIC);
        $cell = 'G1';
        $sheet->setCellValue($cell, '=HEX2DEC(10.1)');
        self::assertEquals(16, $sheet->getCell($cell)->getCalculatedValue());
        $cell = 'F21';
        $sheet->setCellValue($cell, '=HEX2DEC("A.1")');
        self::assertEquals('#NUM!', $sheet->getCell($cell)->getCalculatedValue());
        Functions::setCompatibilityMode(Functions::COMPATIBILITY_OPENOFFICE);
        $cell = 'O1';
        $sheet->setCellValue($cell, '=HEX2DEC(10.1)');
        self::assertEquals('#NUM!', $sheet->getCell($cell)->getCalculatedValue());
        Functions::setCompatibilityMode(Functions::COMPATIBILITY_EXCEL);
        $cell = 'E1';
        $sheet->setCellValue($cell, '=HEX2DEC(10.1)');
        self::assertEquals('#NUM!', $sheet->getCell($cell)->getCalculatedValue(), 'Excel');
        $spreadsheet->disconnectWorksheets();
    }

    /**
     * @dataProvider providerHex2DecArray
     */
    public function testHex2DecArray(array $expectedResult, string $value): void
    {
        $calculation = Calculation::getInstance();

        $formula = "=HEX2DEC({$value})";
        $result = $calculation->_calculateFormulaValue($formula);
        self::assertEquals($expectedResult, $result);
    }

    public function providerHex2DecArray(): array
    {
        return [
            'row/column vector' => [
                [[4, 7, 63, 153, 204, 341]],
                '{"4", "7", "3F", "99", "CC", "155"}',
            ],
        ];
    }
}
