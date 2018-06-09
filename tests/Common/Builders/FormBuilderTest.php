<?php

/**
*  Testing the FormBuilder class
*
*  @author Fabio Mattei
*/
class FormBuilderTest extends PHPUnit_Framework_TestCase {
	
    private $formarray = array(
        1 => array(
            'description' => array(
                'type'  => 'textarea',
                'label' => 'Description:',
                'width' => 'col-sm-12'
            )
        ),
        2 => array(
            'amount' => array(
                'type'  => 'currency',
                'label' => 'Amount (&euro;):',
                'width' => 'col-sm-6'
            ),
            'duedate' => array(
                'type'  => 'date',
                'label' => 'Due date:',
                'width' => 'col-sm-6'
            )
        )
    );
	
	private $xmlString = '<?xml version="1.0" encoding="UTF-8" ?><nodes><description>prova</description><amount>10</amount><duedate>2017-06-26</duedate></nodes>';
	
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
	
	public function testFormContainsTextArea(){
		$form = new Firststep\Common\Builders\FormBuilder;
		$form->setForm( $this->formarray );
		$form->setXmlstring( $this->xmlString );
		$html = $form->createBodyStructure();
		$this->assertTrue(strpos($html, '<textarea class="form-control" rows="5" id="description" name="description">') !== false);
		unset($form);
	}
	
	public function testFormContainsCurrencyField(){
		$form = new Firststep\Common\Builders\FormBuilder;
		$form->setForm( $this->formarray );
		$form->setXmlstring( $this->xmlString );
		$html = $form->createBodyStructure();
		$this->assertTrue(strpos($html, '<input type="number" name="amount"') !== false);
		unset($form);
	}
	
	public function testFormContainsDateField(){
		$form = new Firststep\Common\Builders\FormBuilder;
		$form->setForm( $this->formarray );
		$form->setXmlstring( $this->xmlString );
		$html = $form->createBodyStructure();
		$this->assertTrue(strpos($html, '<input type="text" class="form-control datepicker" name="duedate"') !== false);
		unset($form);
	}
	
	public function testFormContainsTextAreaWithData(){
		$form = new Firststep\Common\Builders\FormBuilder;
		$form->setForm( $this->formarray );
		$form->setXmlstring( $this->xmlString );
		$html = $form->createBodyStructure();
		$this->assertTrue(strpos($html, 'name="description">prova</textarea>') !== false);
		unset($form);
	}
	
	public function testFormContainsCurrencyFieldWithData(){
		$form = new Firststep\Common\Builders\FormBuilder;
		$form->setForm( $this->formarray );
		$form->setXmlstring( $this->xmlString );
		$html = $form->createBodyStructure();
		$this->assertTrue(strpos($html, '<input type="number" name="amount" value="10"') !== false);
		unset($form);
	}
	
	public function testFormContainsDateFieldWithData(){
		$form = new Firststep\Common\Builders\FormBuilder;
		$form->setForm( $this->formarray );
		$form->setXmlstring( $this->xmlString );
		$html = $form->createBodyStructure();
		$this->assertTrue(strpos($html, '<input type="text" class="form-control datepicker" name="duedate" value="26/06/2017"') !== false);
		unset($form);
	}

}
