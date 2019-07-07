<?php

use Firststep\Common\Json\Variables\StringParser;

class StringParserTest extends PHPUnit_Framework_TestCase {

    /**
     * Just check if the YourClass has no syntax error
     *
     * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
     * any typo before you even use this library in a real project.
     *
     */
    public function testIsThereAnySyntaxError(){
        $stringParser = new Firststep\Common\Json\Variables\StringParser;
        $this->assertTrue(is_object($stringParser));
        unset($stringParser);
    }

    public function testMakeAllPresets(){
        $stringParser = new Firststep\Common\Json\Variables\StringParser;
        $this->assertEquals( 'mycostant', $stringParser->parseString('mycostant') );
    }

}

