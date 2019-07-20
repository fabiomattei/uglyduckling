<?php

use Fabiom\UglyDuckling\Common\Utils\StringUtils;

/**
 *  Testing the StringUtils class
 *
 *  @author Fabio Mattei
 */
class StringUtilsTest extends PHPUnit\Framework\TestCase {

    public function testFindCorrectlyAFieldInAQuery(){
        $this->assertTrue( StringUtils::isStringBetween('username', 'SELECT username, email FROM mytable', 'SELECT', 'FROM') );
    }

    public function testCorrectlyDoNotFindANotExistentFieldInAQuery(){
        $this->assertFalse( StringUtils::isStringBetween('address', 'SELECT username, email FROM mytable', 'SELECT', 'FROM') );
    }

    public function testFindCorrectlyAFieldInAQueryCaseUnsensitive(){
        $this->assertTrue( StringUtils::isStringBetweenCaseUnsensitive('uSeRnaMe', 'SELECT username, email FROM mytable', 'SeLeCt', 'fRoM') );
    }

    public function testCorrectlyDoNotFindANotExistentFieldInAQueryCaseUnsensitive(){
        $this->assertFalse( StringUtils::isStringBetweenCaseUnsensitive('address', 'SELECT username, email FROM mytable', 'SeLeCt', 'fRoM') );
    }

}
