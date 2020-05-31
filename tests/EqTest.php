<?php namespace Tests;

use PHPUnit\Framework\TestCase;
use EquationParser\Parse;

class EqTest extends TestCase
{

     /**
     * @dataProvider otherCasesData
     */
    public function testSimpleCases($input_string, $expected_result) 
    {
        $parser = new Parse();
        $result = $parser->eval($input_string);        
        $this->assertEquals($result, $expected_result);
    }
    
    public function otherCasesData() {
        return [
            ['5+1', 6],
            ['5/2', 2.5],
            ['5-2', 3],
            ['6+(5-2)', 9],
            ['((6-3)+(5-2))', 6],
            ['(6/(3-1))+(5+(6/3))', 10],
            ['(6.2/(3-1))+(5.8+(6/3))', 10.9]
        ];
    }

    /**
     * @dataProvider functionCasesData
     */
    public function testFunctionCases($input_string, $expected_result) 
    {
        $parser = new Parse();
        $parser->functions = [
            'min' => [
                'ref' => function($value,$min) {
                    if ($value < $min) return $min;
                    return $value; 
                },
                'arc' => null
            ],
            'max' => [
                'ref' => function($value,$max){
                    if ($value > $max) return $max;
                    return $value;
                },
                'arc' => null
            ],
            'abs' => [
                'ref' => function($value){
                    return abs($value);
                },
                'arc' => null
            ]
        ];

        $result = $parser->eval($input_string);        
        $this->assertEquals($result, $expected_result);
    }
    
    public function functionCasesData() {
        return [
            ['min((5+5),2)', 10],
            ['min((5+5),8)', 10],
            ['min((5+5),15)', 15], 
            ['min((15+5),15)',20],
            ['max((5+5),3)',3],
            ['max((5+5),15)',10],
            ['max((15+5),15)',15],
            ['abs(-1.5)',1.5]
        ];
    }

}