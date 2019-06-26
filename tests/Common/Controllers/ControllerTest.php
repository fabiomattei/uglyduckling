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
		$router = $this->getMockBuilder(Firststep\Common\Router\Router::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
		$setup = $this->getMockBuilder(Firststep\Common\Setup\Setup::class)->getMock();
		$request = $this->getMockBuilder(Firststep\Common\Request\Request::class)->getMock();
		$severWrapper = $this->getMockBuilder(Firststep\Common\Wrappers\ServerWrapper::class)->getMock(); 		
		$sessionWrapper = $this->getMockBuilder(Firststep\Common\Wrappers\SessionWrapper::class)->getMock();
		$securityChecker = $this->getMockBuilder(Firststep\Common\SecurityCheckers\PublicSecurityChecker::class)->getMock();
		$securityChecker->expects($this->once())->method('isSessionValid')->will($this->returnValue(true));
		$dbconnection = $this->getMockBuilder(Firststep\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock(); 
		$redirector = $this->getMockBuilder(Firststep\Common\Redirectors\FakeRedirector::class)->getMock();
		$jsonLoader = $this->getMockBuilder(Firststep\Common\Json\JsonLoader::class)->getMock();
		$messages = $this->getMockBuilder(Firststep\Common\Blocks\BaseHTMLMessages::class)->getMock();
        $htmlTemplateBuilder = $this->getMockBuilder(Firststep\Common\Utils\HtmlTemplateLoader::class)->getMock();
		$echologger = $this->getMockBuilder(Firststep\Common\Loggers\EchoLogger::class)->getMock();
		
		$controller = new Firststep\Common\Controllers\Controller;
		$controller->makeAllPresets(
			$router,
			$setup, 
			$request,
			$severWrapper,
			$sessionWrapper,
			$securityChecker,
			$dbconnection,
			$redirector,
			$jsonLoader,
			$echologger,
			$messages,
            $htmlTemplateBuilder
		);
		$this->assertTrue(is_object($controller));
		unset($controller);
	}

}
