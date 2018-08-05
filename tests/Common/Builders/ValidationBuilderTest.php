<?php

/**
*  Testing the FormBuilder class
*
*  @author Fabio Mattei
*/
class ValidationBuilderTest extends PHPUnit_Framework_TestCase {
	
	private $parameters;

	protected function setUp() {
		$this->parameters = array();
		$this->parameters[0] = new stdClass;
		$this->parameters[0]->name = 'id';
		$this->parameters[0]->validation = 'required|numeric';
		$this->parameters[1] = new stdClass;
		$this->parameters[1]->name = 'name';
		$this->parameters[1]->validation = 'maxlength,15';
	}
	
	/**
	* Just check if the YourClass has no syntax error 
	*
	* This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
	* any typo before you even use this library in a real project.
	*
	*/
	public function testIsThereAnySyntaxError(){
		$val = new Firststep\Common\Builders\ValidationBuilder;
		$this->assertTrue(is_object($val));
		unset($val);
	}
	
	public function testGetValidationRoules(){
		$val = new Firststep\Common\Builders\ValidationBuilder;
		$rules = $val->getValidationRoules( $this->parameters );
		$this->assertCount(2, $rules);
		$this->assertContains('required|numeric', $rules[0]);
		$this->assertContains('maxlength,15', $rules[1]);
		unset($val);
	}
	
	public function testGetValidationFilters(){
		$val = new Firststep\Common\Builders\ValidationBuilder;
		$filters = $val->getValidationFilters( $this->parameters );
		$this->assertCount(2, $filters);
		$this->assertContains('trim', $filters[0]);
		$this->assertContains('trim', $filters[1]);
		unset($val);
	}
	
	public function testPostValidationRoules(){
		$val = new Firststep\Common\Builders\ValidationBuilder;
		$rules = $val->postValidationRoules( $this->parameters );
		$this->assertCount(2, $rules);
		$this->assertContains('required|numeric', $rules['id']);
		$this->assertContains('maxlength,15', $rules['name']);
		unset($val);
	}
	
	public function testPostValidationFilters(){
		$val = new Firststep\Common\Builders\ValidationBuilder;
		$filters = $val->postValidationFilters( $this->parameters );
		$this->assertCount(2, $filters);
		$this->assertContains('trim', $filters['id']);
		$this->assertContains('trim', $filters['name']);
		unset($val);
	}
	
}
