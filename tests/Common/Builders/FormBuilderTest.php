<?php

/**
*  Testing the FormBuilder class
*
*  @author Fabio Mattei
*/
class FormBuilderTest extends PHPUnit_Framework_TestCase {
	
    private $form;
	private $entity;
	
	protected function setUp() {
		$this->entity = new stdClass;
	    $this->entity->fl_id   = 3;	
	    $this->entity->fl_name = 'prova';
		$this->entity->fl_amount = 10;
		$this->entity->fl_duedate = '2017-06-26';
		
		$this->form = new stdClass;
		$this->form->title = "My new form";
		$this->form->submitTitle = "Save";
		$this->form->rows = array();
		$this->form->rows[0] = new stdClass;
		$this->form->rows[0]->row = 1;
		$this->form->rows[0]->fields = array();
		$this->form->rows[0]->fields[0] = new stdClass;
		$this->form->rows[0]->fields[0]->type = 'textarea';
		$this->form->rows[0]->fields[0]->validation = 'max_len,2500';
		$this->form->rows[0]->fields[0]->name = 'name';
		$this->form->rows[0]->fields[0]->label = 'Name';
		$this->form->rows[0]->fields[0]->placeholder = 'Name';
		$this->form->rows[0]->fields[0]->value = 'fl_name';
		$this->form->rows[0]->fields[0]->width = 6;
		$this->form->rows[0]->fields[1] = new stdClass;
		$this->form->rows[0]->fields[1]->type = 'currency';
		$this->form->rows[0]->fields[1]->validation = 'required|numeric';
		$this->form->rows[0]->fields[1]->name = 'amount';
		$this->form->rows[0]->fields[1]->label = 'Amount';
		$this->form->rows[0]->fields[1]->placeholder = '0.00';
		$this->form->rows[0]->fields[1]->value = 'fl_amount';
		$this->form->rows[0]->fields[1]->width = 6;
		$this->form->rows[1] = new stdClass;
		$this->form->rows[1]->row = 2;
		$this->form->rows[1]->fields = array();
		$this->form->rows[1]->fields[0] = new stdClass;
		$this->form->rows[1]->fields[0]->type = 'date';
		$this->form->rows[1]->fields[0]->validation = 'max_len,10';
		$this->form->rows[1]->fields[0]->name = 'duedate';
		$this->form->rows[1]->fields[0]->label = 'Due date';
		$this->form->rows[1]->fields[0]->value = 'fl_duedate';
		$this->form->rows[1]->fields[0]->width = 12;
		$this->form->rows[1]->fields[1] = new stdClass;
		$this->form->rows[1]->fields[1]->type = 'hidden';
		$this->form->rows[1]->fields[1]->validation = 'required|numeric';
		$this->form->rows[1]->fields[1]->name = 'id';
		$this->form->rows[1]->fields[1]->value = 'fl_id';
	}
	
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
		$form->setFormStructure( $this->form );
		$form->setEntity( $this->entity );
		$block = $form->createForm();
		$this->assertTrue(strpos($block->show(), '<textarea id="name" name="name">') !== false);
		unset($form);
	}
	
	public function testFormContainsCurrencyField(){
		$form = new Firststep\Common\Builders\FormBuilder;
		$form->setFormStructure( $this->form );
		$form->setEntity( $this->entity );
		$block = $form->createForm();
		$this->assertTrue(strpos($block->show(), '<input type="number" id="amount" name="amount" value="10" placeholder="0.00" min="0" step="0.01">') !== false);
		unset($form);
	}
	
	public function testFormContainsDateField(){
		$form = new Firststep\Common\Builders\FormBuilder;
		$form->setFormStructure( $this->form );
		$form->setEntity( $this->entity );
		$block = $form->createForm();
		$this->assertTrue(strpos($block->show(), '><input type="text" class="datepicker" id="duedate" name="duedate"') !== false);
		unset($form);
	}
	
	public function testFormContainsTextAreaWithData(){
		$form = new Firststep\Common\Builders\FormBuilder;
		$form->setFormStructure( $this->form );
		$form->setEntity( $this->entity );
		$block = $form->createForm();
		$this->assertTrue(strpos($block->show(), 'name="name">prova</textarea>') !== false);
		unset($form);
	}
	
	public function testFormContainsCurrencyFieldWithData(){
		$form = new Firststep\Common\Builders\FormBuilder;
		$form->setFormStructure( $this->form );
		$form->setEntity( $this->entity );
		$block = $form->createForm();
		$this->assertTrue(strpos($block->show(), 'name="amount" value="10" placeholder="0.00"') !== false);
		unset($form);
	}
	
	public function testFormContainsDateFieldWithData(){
		$form = new Firststep\Common\Builders\FormBuilder;
		$form->setFormStructure( $this->form );
		$form->setEntity( $this->entity );
		$block = $form->createForm();
		$this->assertTrue(strpos($block->show(), 'name="duedate" value="26/06/2017"') !== false);
		unset($form);
	}

}
