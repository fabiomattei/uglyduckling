<?php 

/**
*  Testing the Request class
*
*  @author Fabio Mattei
*/
class URLRedirectorTest extends PHPUnit\Framework\TestCase {

    /**
     * Just check if the Request has no syntax error 
     *
     * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
     * any typo before you even use this library in a real project.
     *
     */
    public function testIsThereAnySyntaxError(){
        $request = new Firststep\Common\Redirectors\URLRedirector( 'http://localhost:18080/' );
	    $this->assertTrue( is_object( $request ) );
        unset( $request );
    }

}
