<?php

use Fabiom\UglyDuckling\Common\Json\Variables\StringParser;

class StringParserTest extends PHPUnit\Framework\TestCase {

    /**
     * Just check if the YourClass has no syntax error
     *
     * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
     * any typo before you even use this library in a real project.
     *
     */
    public function testIsThereAnySyntaxError(){
        $stringParser = new StringParser;
        $this->assertTrue(is_object($stringParser));
        unset($stringParser);
    }

    public function testIWorksWithCostants(){
        $stringParser = new StringParser;
        $this->assertEquals( 'mycostant', $stringParser->parseString('mycostant') );
    }

    public function testIWorksWithOnePostVariable(){
        $stringParser = new StringParser;
        $stringParser->setPostparameters(array( 'mypostvariable' => '1' ));
        $this->assertEquals( '1', $stringParser->parseString('POST[mypostvariable]') );
    }

    public function testIWorksWithTwoPostVariables(){
        $stringParser = new StringParser;
        $stringParser->setPostparameters(array( 'mypostvariable' => '1', 'mysecondpostvariable' => 'fabio' ));
        $this->assertEquals( ' 1 - fabio ', $stringParser->parseString(' POST[mypostvariable] - POST[mysecondpostvariable] ') );
    }

    public function testIWorksWithTwoPostVariableAndTwoGetVariables(){
        $stringParser = new Fabiom\UglyDuckling\Common\Json\Variables\StringParser;
        $stringParser->setPostparameters(array( 'mypostvariable' => '1', 'mysecondpostvariable' => 'fabio' ));
        $stringParser->setGetParameters(array( 'mygetvariable' => '2', 'mysecondgetvariable' => 'bob' ));
        $this->assertEquals( ' 1 - fabio 2 - bob ', $stringParser->parseString(' POST[mypostvariable] - POST[mysecondpostvariable] GET[mygetvariable] - GET[mysecondgetvariable] ') );
    }

    public function testIWorksWithTwoPostVariableTwoGetVariablesAndTwoSessionVariables(){
        $stringParser = new Fabiom\UglyDuckling\Common\Json\Variables\StringParser;
        $stringParser->setPostparameters(array( 'mypostvariable' => '1', 'mysecondpostvariable' => 'fabio' ));
        $stringParser->setGetParameters(array( 'mygetvariable' => '2', 'mysecondgetvariable' => 'bob' ));
        $stringParser->setSessionparameters(array( 'mysessionvariable' => '3', 'mysecondsessionvariable' => 'eve' ));
        $this->assertEquals( ' 1 - fabio 2 - bob 3 - eve  ', $stringParser->parseString(' POST[mypostvariable] - POST[mysecondpostvariable] GET[mygetvariable] - GET[mysecondgetvariable] SESSION[mysessionvariable] - SESSION[mysecondsessionvariable]  ') );
    }

}

