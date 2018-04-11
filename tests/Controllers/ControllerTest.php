<?php 

/**
*  Testing the Controller class
*
*  @author Fabio Mattei
*/
class ControllerTest extends PHPUnit_Framework_TestCase{
	
  /**
  * Just check if the YourClass has no syntax error 
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function testIsThereAnySyntaxError(){
    $request = new Firststep\Request\Request();
    $publicSecurityChecker = new Firststep\SecurityCheckers\PublicSecurityChecker();
    $request->setSecurityChecker( $publicSecurityChecker );
    $controller = new Firststep\Controllers\Controller( 
      new Firststep\Setup\Setup(), 
      $request, 
      new Firststep\Redirectors\FakeRedirector(), 
      new Firststep\Loggers\EchoLogger(),
      new Firststep\Blocks\BaseMessages
    );
	  $this->assertTrue(is_object($controller));
	  unset($controller);
  }
  
}
