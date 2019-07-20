<?php 

/**
*  Testing the BaseHTMLForm class
*
*  @author Fabio Mattei
*/
class BaseFormTest extends PHPUnit\Framework\TestCase{

    private $htmlTemplateLoader;
    private $form;

    protected function setUp(): void {
        $this->htmlTemplateLoader = new \Fabiom\UglyDuckling\Common\Utils\HtmlTemplateLoader();
        $this->htmlTemplateLoader->setPath( 'src/Templates/HTML/' );
        $this->form = new Fabiom\UglyDuckling\Common\Blocks\BaseHTMLForm;
        $this->form->setHtmlTemplateLoader($this->htmlTemplateLoader);
    }
	
  /**
  * Just check if the YourClass has no syntax error 
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function testIsThereAnySyntaxError(){
	$this->assertTrue(is_object($this->form));
	unset($this->form);
  }
  
  /**
  * Checking empty form
  */
  public function testItShowsAnEmpyForm(){
	$this->assertStringContainsString('<form action="" method="POST" class="form-horizontal">', $this->form->show());
	unset($form);
  }

  /**
  * Checking a text field is added to the form
  */
  public function testItShowsAFormWithATextField(){
  $this->form->addTextField( 'myname', 'My label', 'My placeholder', 'My value', '6');
  $this->assertStringContainsString('<label for="myname">My label</label><input class="form-control" type="text" id="myname" name="myname" value="My value" placeholder="My placeholder">', $this->form->show());
  unset($form);
  }
  
  /**
  * Checking a textarea field is added to the form
  */
  public function testItShowsAFormWithATextAreaField(){
	$this->form->addTextAreaField( 'myname', 'My label', 'My value', '6');
	$this->assertStringContainsString('<label for="myname">My label</label><textarea class="form-control" id="myname" name="myname">My value</textarea>', $this->form->show());
	unset($form);
  }
  
  /**
  * Checking a dropdown field is added to the form
  * the possible options are passed as an associative array where the key is the value of the option and the value 
  * is the description of the option: array( '1' => 'Option 1', '2' => 'Option 2' )
  * The value passed to the method is selected: 2 => Option 2
  */
  public function testItShowsAFormWithADropdownField(){
	$this->form->addDropdownField( 'myname', 'My label', array( '1' => 'Option 1', '2' => 'Option 2' ), '2', '6');
	$this->assertStringContainsString('<label for="myname">My label</label><select class="form-control" id="myname" name="myname"><option value="1" >Option 1</option><option value="2" selected="selected">Option 2</option></select>', $this->form->show());
	unset($form);
  }
  
  /**
  * Checking a file upload field is added to the form
  */
  public function testItShowsAFormWithAFileUploadField(){
	$this->form->addFileUploadField( 'myname', 'My label', '6' );
	$this->assertStringContainsString('<label for="myname">My label</label><input class="form-control" type="file" id="myname" name="myname">', $this->form->show());
	unset($form);
  }
  
  /**
  * Checking a helping text is added to the form
  */
  public function testItShowsAFormWithAHelpingTextField(){
	$this->form->addHelpingText( 'Title', 'My description', '6' );
	$this->assertStringContainsString('<h5>Title</h5><p>My description</p>', $this->form->show());
	unset($form);
  }
  
  /**
  * Checking a helping text is added to the form
  */
  public function testItShowsAFormWithAHiddenField(){
	$this->form->addHiddenField( 'myfield', 'My Value' );
	$this->assertStringContainsString('<input type="hidden" name="myfield" value="My Value">', $this->form->show());
	unset($form);
  }
  
  /**
  * Checking a helping text is added to the form
  */
  public function testItShowsAFormWithASubmitButton(){
	$this->form->addSubmitButton( 'myfield', 'My Value' );
	$this->assertStringContainsString('<input class="form-control" type="submit" name="myfield" value="My Value"/>', $this->form->show());
	unset($form);
  }
  
}
