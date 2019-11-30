<?php 

/**
*  Testing the Controller class
*
*  @author Fabio Mattei
*/
class LoginTest extends PHPUnit\Framework\TestCase {
	
	/**
	* Just check if the YourClass has no syntax error 
	*
	* This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
	* any typo before you even use this library in a real project.
	*
	*/
	public function testIsThereAnySyntaxError() {
		$controller = new Fabiom\UglyDuckling\Controllers\Community\Login;
		$this->assertTrue(is_object($controller));
		unset($controller);
	}

	public function testGetRequest() {
		$router = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Router\RoutersContainer::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
		$setup = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Setup\Setup::class)->getMock();
		$request = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Request\Request::class)->getMock(); 
		$severWrapper = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Wrappers\ServerWrapper::class)->getMock(); 		
		$sessionWrapper = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Wrappers\SessionWrapper::class)->getMock();
		$sessionWrapper->expects($this->any())->method('getSessionGroup')->will($this->returnValue('manager'));
		$securityChecker = $this->getMockBuilder(Fabiom\UglyDuckling\Common\SecurityCheckers\PublicSecurityChecker::class)->getMock();
		$dbconnection = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock(); 
		$redirector = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Redirectors\FakeRedirector::class)->getMock();
		$jsonLoader = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Json\JsonLoader::class)->getMock();
		$messages = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Blocks\BaseHTMLMessages::class)->getMock();
        $htmlTemplateBuilder = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Utils\HtmlTemplateLoader::class)->getMock();
        $echologger = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Loggers\EchoLogger::class)->getMock();
        $jsonTemplateFactoriesContainer = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplateFactoriesContainer::class)->getMock();
		
		$controller = new Fabiom\UglyDuckling\Controllers\Community\Login;
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
            $htmlTemplateBuilder,
            $jsonTemplateFactoriesContainer
		);
		$controller->getRequest();
		
		$this->assertTrue(strpos($controller->centralcontainer[0]->show(), 'Sign in to continue to') !== false);
	}
	
	public function testPostRequestWithNoPostParameters() {
		$router = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Router\RoutersContainer::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
		$router->expects($this->once())->method('make_url')->will($this->returnValue(''));
		$setup = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Setup\Setup::class)->getMock();
		$request = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Request\Request::class)->getMock();
		$severWrapper = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Wrappers\ServerWrapper::class)->getMock(); 		
		$sessionWrapper = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Wrappers\SessionWrapper::class)->getMock();
		$sessionWrapper->expects($this->any())->method('getSessionGroup')->will($this->returnValue('manager'));
		$securityChecker = $this->getMockBuilder(Fabiom\UglyDuckling\Common\SecurityCheckers\PublicSecurityChecker::class)->getMock();
		$securityChecker->expects($this->once())->method('isSessionValid')->will($this->returnValue(true));
		$dbconnection = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock(); 
		$redirector = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Redirectors\FakeRedirector::class)->getMock();
		$jsonLoader = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Json\JsonLoader::class)->getMock();
		$messages = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Blocks\BaseHTMLMessages::class)->getMock();
        $htmlTemplateBuilder = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Utils\HtmlTemplateLoader::class)->getMock();
        $echologger = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Loggers\EchoLogger::class)->getMock();
        $jsonTemplateFactoriesContainer = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplateFactoriesContainer::class)->getMock();
		
		$controller = new Fabiom\UglyDuckling\Controllers\Community\Login;
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
            $htmlTemplateBuilder,
            $jsonTemplateFactoriesContainer
		);
		$controller->setPostParameters( array() );
		$controller->userCanLogIn = $this->getMockBuilder(Fabiom\UglyDuckling\BusinessLogic\User\UseCases\UserCanLogIn::class)->getMock();
		$controller->userCanLogIn->expects($this->once())->method('getUserCanLogIn')->will($this->returnValue(false));
		$router->expects($this->once())->method('make_url');
		$controller->postRequest();
	}
	
	public function testPostRequestTrue() {
		$router = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Router\RoutersContainer::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
		$router->expects($this->once())->method('make_url')->will($this->returnValue(''));
		$setup = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Setup\Setup::class)->getMock();
		$request = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Request\Request::class)->getMock();
		$severWrapper = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Wrappers\ServerWrapper::class)->getMock();
		$severWrapper->expects($this->any())->method('getRemoteAddress');
		$severWrapper->expects($this->any())->method('getHttpUserAgent');
		$sessionWrapper = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Wrappers\SessionWrapper::class)->getMock();
		$sessionWrapper->expects($this->any())->method('getSessionGroup')->will($this->returnValue('manager'));
		$securityChecker = $this->getMockBuilder(Fabiom\UglyDuckling\Common\SecurityCheckers\PublicSecurityChecker::class)->getMock();
		$securityChecker->expects($this->once())->method('isSessionValid')->will($this->returnValue(true));
		$sessionWrapper->expects($this->once())->method('setSessionUserId');
		$sessionWrapper->expects($this->once())->method('setSessionUsername');
		$sessionWrapper->expects($this->once())->method('setSessionLoggedIn');
		$sessionWrapper->expects($this->once())->method('setSessionIp');
		$sessionWrapper->expects($this->once())->method('setSessionUserAgent');
		$sessionWrapper->expects($this->once())->method('setSessionLastLogin');
		$dbconnection = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock(); 
		$redirector = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Redirectors\FakeRedirector::class)->getMock();
		$jsonLoader = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Json\JsonLoader::class)->getMock();
		$e = new stdClass; 
		$e->defaultaction='defaultaction';
		$jsonLoader->expects($this->once())->method('loadResource')->will($this->returnValue( $e ));
		$messages = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Blocks\BaseHTMLMessages::class)->getMock();
        $htmlTemplateBuilder = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Utils\HtmlTemplateLoader::class)->getMock();
        $echologger = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Loggers\EchoLogger::class)->getMock();
        $jsonTemplateFactoriesContainer = $this->getMockBuilder(Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplateFactoriesContainer::class)->getMock();
		
		$controller = new Fabiom\UglyDuckling\Controllers\Community\Login;
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
            $htmlTemplateBuilder,
            $jsonTemplateFactoriesContainer
		);
		$controller->setPostParameters( array( 'email' => '' ) );
		$controller->userCanLogIn = $this->getMockBuilder(Fabiom\UglyDuckling\BusinessLogic\User\UseCases\UserCanLogIn::class)->getMock();
		$controller->userCanLogIn->expects($this->once())->method('getUserCanLogIn')->will($this->returnValue(true));

		$user = new stdClass();
		$user->usr_id = 1;
		$user->usr_name = "fabio";
		$user->usr_usrofid = 99;
		$user->usr_defaultgroup = 'manager';
		$controller->userDao = $this->getMockBuilder(Fabiom\UglyDuckling\BusinessLogic\User\Daos\UserDao::class)->getMock();
		$controller->userDao->expects($this->once())->method('getOneByFields')->will($this->returnValue($user));
		$router->expects($this->once())->method('make_url');
		$controller->postRequest();
	}

}
