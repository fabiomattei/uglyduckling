<?php 

/**
*  Testing the BaseForm class
*
*  @author Fabio Mattei
*/
class BaseFormTest extends PHPUnit_Framework_TestCase{
	
  /**
  * Just check if the YourClass has no syntax error 
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function testIsThereAnySyntaxError(){
	$form = new Firststep\Blocks\BaseForm;
	$this->assertTrue(is_object($form));
	unset($form);
  }
  
  /**
  * Checking empty form
  */
  public function testItShowsAnEmpyForm(){
	$form = new Firststep\Blocks\BaseForm;
	$this->assertTrue($form->show() == '<h3></h3><form action="" method="POST" class="form-horizontal"></form>');
	unset($form);
  }
  
  /**
  * Checking a text field is added to the form
  */
  public function testItShowsAFormWithATextField(){
	$form = new Firststep\Blocks\BaseForm;
	$form->addTextField( 'myname', 'My label', 'My placeholder', 'My value');
	$this->assertTrue(strpos($form->show(), '<label for="myname">My label</label><input type="text" id="myname" name="myname" value="My value" placeholder="My placeholder">') !== false);
	unset($form);
  }
  
  /**
  * Checking a textarea field is added to the form
  */
  public function testItShowsAFormWithAaddTextAreaField(){
	$form = new Firststep\Blocks\BaseForm;
	$form->addTextAreaField( 'myname', 'My label', 'My value');
	$this->assertTrue(strpos($form->show(), '<label for="myname">My label</label><textarea id="myname" name="myname">My value</textarea>') !== false);
	unset($form);
  }
  
  
  
}
