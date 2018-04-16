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
    $request = new Firststep\Common\Request\Request();
    $publicSecurityChecker = new Firststep\Common\SecurityCheckers\PublicSecurityChecker();
    $request->setSecurityChecker( $publicSecurityChecker );
    $controller = new Firststep\Common\Controllers\Controller( 
      new Firststep\Common\Setup\Setup(), 
      $request, 
      new Firststep\Common\Redirectors\FakeRedirector(), 
      new Firststep\Common\Loggers\EchoLogger(),
      new Firststep\Common\Blocks\BaseMessages
    );
	  $this->assertTrue(is_object($controller));
	  unset($controller);
  }
  
}
