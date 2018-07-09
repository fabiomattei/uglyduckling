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
        $request->setServerRequestURI( '/action/par1/par2/par3.html' );
        $this->assertTrue( $request->getAction() == 'action' );
        unset( $request );
    }

    public function testOnceWeSetRequestItGetsTheParameters(){
        $request = new Firststep\Common\Request\Request;
        $request->setServerRequestURI( '/action/par1/par2/par3.html' );
        $this->assertTrue( count($request->getParameters()) == 3 );
        $this->assertTrue( $request->getParameters()[0] == 'par1' );
        $this->assertTrue( $request->getParameters()[1] == 'par2' );
        $this->assertTrue( $request->getParameters()[2] == 'par3' );
        unset( $request );
    }
  
}
