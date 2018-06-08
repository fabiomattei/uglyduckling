<?php 

/**
*  Testing the Request class
*
*  @author Fabio Mattei
*/
class RequestTest extends PHPUnit_Framework_TestCase {

    /**
     * Just check if the Request has no syntax error 
     *
     * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
     * any typo before you even use this library in a real project.
     *
     */
    public function testIsThereAnySyntaxError(){
        $request = new Firststep\Common\Request\Request;
	      $this->assertTrue( is_object( $request ) );
        unset( $request );
    }

    public function testOnceWeSetRequestItGetsTheAction(){
        $request = new Firststep\Common\Request\Request;
        $request->setServerRequestURI( '/action/parm1/par2/par3.html' );
        $this->assertTrue( $request->getAction() == 'action' );
        unset( $request );
    }
  
}
