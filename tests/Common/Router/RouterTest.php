<?php 

/**
*  Testing the Router class
*
*  @author Fabio Mattei
*/
class RouterTest extends PHPUnit\Framework\TestCase {
	
	/**
	* Just check if the YourClass has no syntax error 
	*
	* This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
	* any typo before you even use this library in a real project.
	*
	*/
	public function testIsThereAnySyntaxError() {
		$router = new Fabiom\UglyDuckling\Common\Router\Router( 'http://localhost:18080/' );
		$this->assertTrue( is_object( $router ) );
		unset( $router );
	}

	public function testMakeUrlOnlyWithAnAction() {
		$router = new Fabiom\UglyDuckling\Common\Router\Router( 'http://localhost:18080/' );
		$this->assertSame( $router->make_url( 'dashboard' ), 'http://localhost:18080/dashboard.html' );
        unset( $router );
	}
	
	public function testMakeUrlWithActionAndParameters() {
		$router = new Fabiom\UglyDuckling\Common\Router\Router( 'http://localhost:18080/' );
		$this->assertSame( $router->make_url( 'dashboard', 'id=0&par=1' ), 'http://localhost:18080/dashboard.html?id=0&par=1' );
        unset( $router );
	}

	public function testReturnLoginControllerIfCalledWithNoAction() {
		$router = new Fabiom\UglyDuckling\Common\Router\Router( 'http://localhost:18080/' );
		$this->assertSame( get_class( $router->getController( '' ) ), 'Fabiom\UglyDuckling\Controllers\Community\Login' );
        unset( $router );
	}
 	
 	public function testReturnAdminDashboardControllerIfCalledWithActionadmindashboard() {
		$router = new Fabiom\UglyDuckling\Common\Router\Router( 'http://localhost:18080/' );
		$this->assertSame( get_class( $router->getController( 'admindashboard' ) ), 'Fabiom\UglyDuckling\Controllers\Admin\Dashboard\AdminDashboard' );
        unset( $router );
	}
   
}
