<?php

/**
*  Testing the InfoBuilder class
*
*  @author Fabio Mattei
*/
class InfoBuilderTest extends PHPUnit_Framework_TestCase {
	
    private $form;
	private $entity;
	
	protected function setUp() {
		$this->entity = new stdClass;
	    $this->entity->fl_id   = 3;	
	    $this->entity->fl_name = 'prova';
		$this->entity->fl_amount = 10;
		$this->entity->fl_duedate = '2017-06-26';
		
		$this->form = new stdClass;
		$this->form->title = "My new info box";
		$this->form->rows = array();
		$this->form->rows[0] = new stdClass;
		$this->form->rows[0]->row = 1;
		$this->form->rows[0]->fields = array();
		$this->form->rows[0]->fields[0] = new stdClass;
		$this->form->rows[0]->fields[0]->type = 'textarea';
		$this->form->rows[0]->fields[0]->label = 'Name';
		$this->form->rows[0]->fields[0]->value = 'fl_name';
		$this->form->rows[0]->fields[0]->width = 6;
		$this->form->rows[0]->fields[1] = new stdClass;
		$this->form->rows[0]->fields[1]->type = 'currency';
		$this->form->rows[0]->fields[1]->label = 'Amount';
		$this->form->rows[0]->fields[1]->value = 'fl_amount';
		$this->form->rows[0]->fields[1]->width = 6;
		$this->form->rows[1] = new stdClass;
		$this->form->rows[1]->row = 2;
		$this->form->rows[1]->fields = array();
		$this->form->rows[1]->fields[0] = new stdClass;
		$this->form->rows[1]->fields[0]->type = 'date';
		$this->form->rows[1]->fields[0]->label = 'Due date';
		$this->form->rows[1]->fields[0]->value = 'fl_duedate';
		$this->form->rows[1]->fields[0]->width = 12;
	}
	
	/**
	* Just check if the YourClass has no syntax error 
	*
	* This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
	* any typo before you even use this library in a real project.
	*
	*/
	public function testIsThereAnySyntaxError(){
		$form = new Firststep\Common\Builders\InfoBuilder;
		$this->assertTrue(is_object($form));
		unset($form);
	}
	
	public function testInfoContainsTextArea(){
		$form = new Firststep\Common\Builders\InfoBuilder;
		$form->setFormStructure( $this->form );
		$form->setEntity( $this->entity );
		$block = $form->createInfo();
		$this->assertTrue(strpos($block->show(), '<p>prova</p>') !== false);
		unset($form);
	}
	
	public function testInfoContainsCurrencyFieldWithData(){
		$form = new Firststep\Common\Builders\InfoBuilder;
		$form->setFormStructure( $this->form );
		$form->setEntity( $this->entity );
		$block = $form->createInfo();
		$this->assertTrue(strpos($block->show(), '<p>10</p>') !== false);
		unset($form);
	}
	
	public function testInfoContainsDateFieldWithData(){
		$form = new Firststep\Common\Builders\InfoBuilder;
		$form->setFormStructure( $this->form );
		$form->setEntity( $this->entity );
		$block = $form->createInfo();
		$this->assertTrue(strpos($block->show(), '<p>26/06/2017</p>') !== false);
		unset($form);
	}

}
