<?php

namespace PhpOffice\PhpSpreadsheetTests\Calculation\Functions\Engineering;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Calculation\Exception as CalcExp;
use PhpOffice\PhpSpreadsheet\Calculation\Functions;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PHPUnit\Framework\TestCase;

class Oct2BinTest extends TestCase
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
     * @dataProvider providerOCT2BIN
     *
     * @param mixed $expectedResult
     * @param mixed $formula
     */
    public function testOCT2BIN($expectedResult, $formula): void
    {
        if ($expectedResult === 'exception') {
            $this->expectException(CalcExp::class);
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A2', 101);
        $sheet->getCell('A1')->setValue("=OCT2BIN($formula)");
        $result = $sheet->getCell('A1')->getCalculatedValue();
        self::assertEquals($expectedResult, $result);
        $spreadsheet->disconnectWorksheets();
    }

    public function providerOCT2BIN(): array
    {
        return require 'tests/data/Calculation/Engineering/OCT2BIN.php';
    }

    /**
     * @dataProvider providerOCT2BIN
     *
     * @param mixed $expectedResult
     * @param mixed $formula
     */
    public function testOCT2BINOds($expectedResult, $formula): void
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
        $sheet->setCellValue('A2', 101);
        $sheet->getCell('A1')->setValue("=OCT2BIN($formula)");
        $result = $sheet->getCell('A1')->getCalculatedValue();
        self::assertEquals($expectedResult, $result);
        $spreadsheet->disconnectWorksheets();
    }

    public function testOCT2BINFrac(): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        Functions::setCompatibilityMode(Functions::COMPATIBILITY_GNUMERIC);
        $cell = 'G1';
        $sheet->setCellValue($cell, '=HEX2BIN(10.1)');
        self::assertEquals('10000', $sheet->getCell($cell)->getCalculatedValue());
        Functions::setCompatibilityMode(Functions::COMPATIBILITY_OPENOFFICE);
        $cell = 'O1';
        $sheet->setCellValue($cell, '=OCT2BIN(10.1)');
        self::assertEquals('#NUM!', $sheet->getCell($cell)->getCalculatedValue());
        Functions::setCompatibilityMode(Functions::COMPATIBILITY_EXCEL);
        $cell = 'E1';
        $sheet->setCellValue($cell, '=OCT2BIN(10.1)');
        self::assertEquals('#NUM!', $sheet->getCell($cell)->getCalculatedValue());
        $spreadsheet->disconnectWorksheets();
    }

    /**
     * @dataProvider providerOct2BinArray
     */
    public function testOct2BinArray(array $expectedResult, string $value): void
    {
        $calculation = Calculation::getInstance();

        $formula = "=OCT2BIN({$value})";
        $result = $calculation->_calculateFormulaValue($formula);
        self::assertEquals($expectedResult, $result);
    }

    public function providerOct2BinArray(): array
    {
        return [
            'row/column vector' => [
                [['100', '111', '111111', '10011001', '11001100', '101010101']],
                '{"4", "7", "77", "231", "314", "525"}',
            ],
        ];
    }
}
