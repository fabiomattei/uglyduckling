<?php 

/**
*  Testing the PublicSecurityChecker class
*
*  @author Fabio Mattei
*/
class PublicSecurityCheckerTest extends PHPUnit_Framework_TestCase{
	
  /**
  * Just check if the YourClass has no syntax error 
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function testIsThereAnySyntaxError(){
	 $checker = new Firststep\SecurityCheckers\PublicSecurityChecker();
	 $this->assertTrue(is_object($checker));
	 unset($checker);
  }


  
}
