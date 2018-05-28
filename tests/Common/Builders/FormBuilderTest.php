<?

/**
*  Testing the BaseForm class
*
*  @author Fabio Mattei
*/
class FormBuilderTest extends PHPUnit_Framework_TestCase{
	
  /**
  * Just check if the YourClass has no syntax error 
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function testIsThereAnySyntaxError(){
	  $form = new Firststep\Common\Builders\FormBuilder;
	  $this->assertTrue(is_object($form));
	  unset($form);
  }
  
}
