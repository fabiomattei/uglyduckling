<?php 

/**
*  Testing the Controller class
*
*  @author Fabio Mattei
*/
class IndexTest extends PHPUnit_Framework_TestCase {
	
	/**
	* Just check if the YourClass has no syntax error 
	*
	* This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
	* any typo before you even use this library in a real project.
	*
	*/
	public function testIsThereAnySyntaxError() {
		$controller = new Firststep\Controllers\Community\Index;
		$this->assertTrue(is_object($controller));
		unset($controller);
	}

	public function testGetRequest() {
		$router = $this->getMockBuilder(Firststep\Common\Router\Router::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
		$setup = $this->getMockBuilder(Firststep\Common\Setup\Setup::class)->getMock();
		$request = $this->getMockBuilder(Firststep\Common\Request\Request::class)->getMock(); 
		$request->expects($this->once())->method('isSessionValid')->will($this->returnValue(true));
		$severWrapper = $this->getMockBuilder(Firststep\Common\Wrappers\ServerWrapper::class)->getMock(); 		
		$sessionWrapper = $this->getMockBuilder(Firststep\Common\Wrappers\SessionWrapper::class)->getMock();
		$dbconnection = $this->getMockBuilder(Firststep\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock(); 
		$redirector = $this->getMockBuilder(Firststep\Common\Redirectors\FakeRedirector::class)->getMock();
		$messages = $this->getMockBuilder(Firststep\Common\Blocks\BaseMessages::class)->getMock();
		$echologger = $this->getMockBuilder(Firststep\Common\Loggers\EchoLogger::class)->getMock();
		
		$controller = new Firststep\Controllers\Community\Index;
		$controller->makeAllPresets(
			$router,
			$setup, 
			$request,
			$severWrapper,
			$sessionWrapper,
			$dbconnection,
			$redirector, 
			$echologger,
			$messages 
		);
		$controller->getRequest();
		
		$this->assertTrue(strpos($controller->centralcontainer[0]->show(), 'Sign in to continue to') !== false);
	}
	
	public function testPostRequest() {
		$router = $this->getMockBuilder(Firststep\Common\Router\Router::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
		$router->expects($this->once())->method('make_url')->will($this->returnValue(''));
		$setup = $this->getMockBuilder(Firststep\Common\Setup\Setup::class)->getMock();
		$request = $this->getMockBuilder(Firststep\Common\Request\Request::class)->getMock(); 
		$request->expects($this->once())->method('isSessionValid')->will($this->returnValue(true));
		$severWrapper = $this->getMockBuilder(Firststep\Common\Wrappers\ServerWrapper::class)->getMock(); 		
		$sessionWrapper = $this->getMockBuilder(Firststep\Common\Wrappers\SessionWrapper::class)->getMock();
		$dbconnection = $this->getMockBuilder(Firststep\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock(); 
		$redirector = $this->getMockBuilder(Firststep\Common\Redirectors\FakeRedirector::class)->getMock();
		$messages = $this->getMockBuilder(Firststep\Common\Blocks\BaseMessages::class)->getMock();
		$echologger = $this->getMockBuilder(Firststep\Common\Loggers\EchoLogger::class)->getMock();
		
		$controller = new Firststep\Controllers\Community\Index;
		$controller->makeAllPresets(
			$router,
			$setup, 
			$request,
			$severWrapper,
			$sessionWrapper,
			$dbconnection,
			$redirector, 
			$echologger,
			$messages 
		);
		$controller->userCanLogIn = $this->getMockBuilder(Firststep\BusinessLogic\User\UseCases\UserCanLogIn::class)->getMock();
		$controller->userCanLogIn->expects($this->once())->method('getUserCanLogIn')->will($this->returnValue(false));
		$router->expects($this->once())->method('make_url');
		$controller->postRequest();
	}
	
	public function testPostRequestTrue() {
		$router = $this->getMockBuilder(Firststep\Common\Router\Router::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
		$router->expects($this->once())->method('make_url')->will($this->returnValue(''));
		$setup = $this->getMockBuilder(Firststep\Common\Setup\Setup::class)->getMock();
		$request = $this->getMockBuilder(Firststep\Common\Request\Request::class)->getMock(); 
		$request->expects($this->once())->method('isSessionValid')->will($this->returnValue(true));
		$severWrapper = $this->getMockBuilder(Firststep\Common\Wrappers\ServerWrapper::class)->getMock();
		$sessionWrapper = $this->getMockBuilder(Firststep\Common\Wrappers\SessionWrapper::class)->getMock();
		$sessionWrapper->expects($this->once())->method('setSessionUserId');
		$sessionWrapper->expects($this->once())->method('setSessionUsername');
		$sessionWrapper->expects($this->once())->method('setSessionLoggedIn');
		$sessionWrapper->expects($this->once())->method('setSessionIp');
		$sessionWrapper->expects($this->once())->method('setSessionUserAgent');
		$sessionWrapper->expects($this->once())->method('setSessionLastLogin');
		$dbconnection = $this->getMockBuilder(Firststep\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock(); 
		$redirector = $this->getMockBuilder(Firststep\Common\Redirectors\FakeRedirector::class)->getMock();
		$messages = $this->getMockBuilder(Firststep\Common\Blocks\BaseMessages::class)->getMock();
		$echologger = $this->getMockBuilder(Firststep\Common\Loggers\EchoLogger::class)->getMock();
		
		$controller = new Firststep\Controllers\Community\Index;
		$controller->makeAllPresets(
			$router,
			$setup, 
			$request,
			$severWrapper,
			$sessionWrapper,
			$dbconnection,
			$redirector, 
			$echologger,
			$messages 
		);
		$controller->userCanLogIn = $this->getMockBuilder(Firststep\BusinessLogic\User\UseCases\UserCanLogIn::class)->getMock();
		$controller->userCanLogIn->expects($this->once())->method('getUserCanLogIn')->will($this->returnValue(true));
		$controller->userDao = $this->getMockBuilder(Firststep\BusinessLogic\User\Daos\UserDao::class)->getMock();
		$controller->userDao->expects($this->once())->method('getOneByFields');
		$router->expects($this->once())->method('make_url');
		$controller->postRequest();
	}

}
