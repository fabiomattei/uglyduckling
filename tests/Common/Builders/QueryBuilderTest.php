<?php

/**
*  Testing the FormBuilder class
*
*  @author Fabio Mattei
*/
class QuryBuilderTest extends PHPUnit_Framework_TestCase {
	
    private $query;
	private $parameters;

	protected function setUp() {
		$this->query = new stdClass;
		$this->query->type = "select";
		$this->query->entity = "mysqltablename";
		$this->query->fields = array();
		$this->query->fields[0] = "myt_field1";
		$this->query->fields[1] = "myt_field2";
		$this->query->fields[2] = "myt_field3";
		$this->query->joins = array();
		$this->query->joins[0] = new stdClass;
		$this->query->joins[0]->type = "join";
		$this->query->joins[0]->entity = "mysecondtable";
		$this->query->joins[0]->joinon = "myifled_1 = myfield2";
		$this->query->joins[1] = new stdClass;
		$this->query->joins[1]->type = "left";
		$this->query->joins[1]->entity = "mythirdtable";
		$this->query->joins[1]->joinon = "myifled_2 = myfield3";
		$this->query->conditions = array();
		$this->query->conditions[0] = new stdClass;
		$this->query->conditions[0]->field = "id";
		$this->query->conditions[0]->operator = "=";
		$this->query->conditions[0]->value = "id";

		$this->parameters = new stdClass;
	}
	
	/**
	* Just check if the YourClass has no syntax error 
	*
	* This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
	* any typo before you even use this library in a real project.
	*
	*/
	public function testIsThereAnySyntaxError(){
		$query = new Firststep\Common\Builders\QueryBuilder;
		$this->assertTrue(is_object($query));
		unset($query);
	}
	
	public function testCreateQuery(){
		$query = new Firststep\Common\Builders\QueryBuilder;
		$query->setQueryStructure( $this->query );
		$query->setParameters( $this->parameters );
		$sqlquery = $query->createQuery();
		$this->assertContains('SELECT', $sqlquery);
		$this->assertContains('myt_field1, myt_field2, myt_field3', $sqlquery);
		$this->assertContains('FROM mysqltablename', $sqlquery);
		$this->assertContains('JOIN mysecondtable ON myifled_1 = myfield2', $sqlquery);
		$this->assertContains('LEFT JOIN mythirdtable ON myifled_2 = myfield3', $sqlquery);
		$this->assertContains('WHERE id = :id', $sqlquery);
		unset($query);
	}



}
