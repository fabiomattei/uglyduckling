<?php 

/**
*  Testing the Controller class
*
*  @author Fabio Mattei
*/
class ControllerTest extends PHPUnit_Framework_TestCase {
	
	/**
	* Just check if the YourClass has no syntax error 
	*
	* This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
	* any typo before you even use this library in a real project.
	*
	*/
	public function testIsThereAnySyntaxError(){
		$controller = new Firststep\Common\Controllers\Controller;
		$this->assertTrue(is_object($controller));
		unset($controller);
	}

	public function testMakeAllPresets(){
		$setup = $this->getMockBuilder(Firststep\Common\Setup\Setup::class)->getMock();
		$request = $this->getMockBuilder(Firststep\Common\Request\Request::class)->getMock(); 
		$request->expects($this->once())->method('isSessionValid')->will($this->returnValue(true));
		$redirector = $this->getMockBuilder(Firststep\Common\Redirectors\FakeRedirector::class)->getMock();
		$messages = $this->getMockBuilder(Firststep\Common\Blocks\BaseMessages::class)->getMock();
		$echologger = $this->getMockBuilder(Firststep\Common\Loggers\EchoLogger::class)->getMock();
		
		$controller = new Firststep\Common\Controllers\Controller;
		$controller->makeAllPresets( 
			$setup, 
			$request, 
			$redirector, 
			$echologger,
			$messages 
		);
		$this->assertTrue(is_object($controller));
		unset($controller);
	}

}
