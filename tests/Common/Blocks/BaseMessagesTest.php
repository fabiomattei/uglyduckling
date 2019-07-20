<?php

/**
*  Testing the BaseHTMLForm class
*
*  @author Fabio Mattei
*/
class BaseMessagesTest extends PHPUnit\Framework\TestCase{
	
  /**
  * Just check if the YourClass has no syntax error 
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function testIsThereAnySyntaxError(){
	$form = new Fabiom\UglyDuckling\Common\Blocks\BaseHTMLMessages;
	$this->assertTrue(is_object($form));
	unset($form);
  }
  
}

