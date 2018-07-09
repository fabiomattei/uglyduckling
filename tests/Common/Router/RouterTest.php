<?php 

/**
*  Testing the Router class
*
*  @author Fabio Mattei
*/
class RouterTest extends PHPUnit_Framework_TestCase {
	
	/**
	* Just check if the YourClass has no syntax error 
	*
	* This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
	* any typo before you even use this library in a real project.
	*
	*/
	public function testIsThereAnySyntaxError() {
		$router = new Firststep\Common\Router\Router( 'http://localhost:18080/' );
		$this->assertTrue( is_object( $router ) );
		unset( $router );
	}

	public function testMakeUrlOnlyWithAnAction() {
		$router = new Firststep\Common\Router\Router( 'http://localhost:18080/' );
		$this->assertSame( $router->make_url( 'Dashobard' ), 'http://localhost:18080/Dashobard.html' );
        unset( $router );
	}
  
}
