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
		$redirector = new Fabiom\UglyDuckling\Common\Redirectors\FakeRedirector;
		$jsonLoader = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Json\JsonLoader::class)->getMock();
        $messagesBlock = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Blocks\BaseHTMLMessages::class)->getMock();
        $htmlTemplateLoader = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Utils\HtmlTemplateLoader::class)->getMock();
		$echologger = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Loggers\EchoLogger::class)->getMock();
        $jsonTemplateFactoriesContainer = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplateFactoriesContainer::class)->getMock();

        $pageStatus = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Status\PageStatus::class)->getMock();
        $pageStatus->setRequest($request);
        $pageStatus->setServerWrapper($severWrapper);
        $pageStatus->setSessionWrapper($sessionWrapper);
        $pageStatus->setGetParameters( $_GET );
        $pageStatus->setPostParameters( $_POST );
        $pageStatus->setFilesParameters( $_FILES );
        $pageStatus->setDbconnection( $dbconnection );
        // $pageStatus->setQueryExecutor( $queryExecutor );

        $applicationBuilder = new Fabiom\UglyDuckling\Common\Status\ApplicationBuilder;
        $applicationBuilder->setRouterContainer($routersContainer);
        $applicationBuilder->setSetup($setup);
        $applicationBuilder->setSecurityChecker($securityChecker);
        $applicationBuilder->setRedirector($redirector);
        $applicationBuilder->setJsonloader($jsonLoader);
        $applicationBuilder->setLogger($echologger);
        $applicationBuilder->setMessages($messagesBlock);
        $applicationBuilder->setHtmlTemplateLoader($htmlTemplateLoader);
        $applicationBuilder->setJsonTemplateFactoriesContainer($jsonTemplateFactoriesContainer);

		$controller = new Fabiom\UglyDuckling\Common\Controllers\Controller;
		$controller->makeAllPresets(
            $applicationBuilder,
            $pageStatus
		);
		$this->assertTrue(is_object($controller));
		unset($controller);
	}

}
