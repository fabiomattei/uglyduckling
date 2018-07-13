<?php 

/**
*  Testing the JsonParametersParser class
*
*  @author Fabio Mattei
*/
class JsonParametersParserTest extends PHPUnit_Framework_TestCase {
	
	private $json = '{
  "request": {
    "parameters": [
      { "type":"int", "validation":"required|int", "name":"1" }
    ]
  }
}';

    /**
     * Just check if the JsonParametersParser has no syntax error 
     *
     * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
     * any typo before you even use this library in a real project.
     *
     */
    public function testIsThereAnySyntaxError(){
        $parser = new Firststep\Common\Json\JsonParametersParser;
	    $this->assertTrue( is_object( $parser ) );
        unset( $parser );
    }
	
    public function testParseResourceForParametersValidationRoules(){
		$resource = json_decode($this->json);
		$parameters = Firststep\Common\Json\JsonParametersParser::parseResourceForParametersValidationRoules($resource);
        $this->assertNotEmpty($parameters['rules']);
		$this->assertNotEmpty($parameters['filters']);
		$this->assertCount(1, $parameters['rules']);
		$this->assertCount(1, $parameters['filters']);
		$this->assertSame('required|int', $parameters['rules'][1]);
		$this->assertSame('trim', $parameters['filters'][1]);
    }
}
