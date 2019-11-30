<?php

/**
*  Testing the Controller class
*
*  @author Fabio Mattei
*/
class ControllerTest extends PHPUnit\Framework\TestCase {
	
	/**
	* Just check if the YourClass has no syntax error 
	*
	* This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
	* any typo before you even use this library in a real project.
	*
	*/
	public function testIsThereAnySyntaxError(){
		$controller = new Fabiom\UglyDuckling\Common\Controllers\Controller;
		$this->assertTrue(is_object($controller));
		unset($controller);
	}

	public function testMakeAllPresets(){
		$routersContainer = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Router\RoutersContainer::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
		$setup = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Setup\Setup::class)->getMock();
		$request = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Request\Request::class)->getMock();
		$severWrapper = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Wrappers\ServerWrapper::class)->getMock(); 		
		$sessionWrapper = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Wrappers\SessionWrapper::class)->getMock();
		$securityChecker = $this->getMockBuilder(Fabiom\UglyDuckling\Common\SecurityCheckers\PublicSecurityChecker::class)->getMock();
		$securityChecker->expects($this->once())->method('isSessionValid')->will($this->returnValue(true));
		$dbconnection = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock(); 
		$redirector = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Redirectors\FakeRedirector::class)->getMock();
		$jsonLoader = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Json\JsonLoader::class)->getMock();
		$logger = new \Fabiom\UglyDuckling\Common\Loggers\EchoLogger();
		$messages = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Blocks\BaseHTMLMessages::class)->getMock();
        $htmlTemplateBuilder = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Utils\HtmlTemplateLoader::class)->getMock();
		$echologger = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Loggers\EchoLogger::class)->getMock();
        $jsonTemplateFactoriesContainer = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplateFactoriesContainer::class)->getMock();

		$controller = new Fabiom\UglyDuckling\Common\Controllers\Controller;
		$controller->makeAllPresets(
            $routersContainer,
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
            $htmlTemplateBuilder,
            $jsonTemplateFactoriesContainer
		);
		$this->assertTrue(is_object($controller));
		unset($controller);
	}

}
