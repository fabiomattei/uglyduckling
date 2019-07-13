<?php 

/**
*  Testing the PublicSecurityChecker class
*
*  @author Fabio Mattei
*/
class PrivateSecurityCheckerTest extends PHPUnit\Framework\TestCase{
	
  /**
  * Just check if the YourClass has no syntax error 
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function testIsThereAnySyntaxError(){
	 $checker = new Firststep\Common\SecurityCheckers\PrivateSecurityChecker();
	 $this->assertTrue(is_object($checker));
	 unset($checker);
  }

  public function testIsSessionValid() {
   $checker = new Firststep\Common\SecurityCheckers\PrivateSecurityChecker();
   $this->assertTrue($checker->isSessionValid(
    1, 
    ':: 1', 
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_4) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/11.1 Safari/605.1.15', 
    1523437554, 
    ':: 1', 
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_4) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/11.1 Safari/605.1.15'));
   unset($checker);
  }
  
}
