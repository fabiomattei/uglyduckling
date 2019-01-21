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

		$this->form = json_decode('{ 
	"name": "formrequestv1",
	"metadata": { "type":"form", "version": "1" },
	"allowedgroups": [ "administrationgroup", "teachergroup", "managergroup" ],
	"get": {
		"request": {
			"parameters": [
				{ "type":"integer", "validation":"required|integer", "name":"id" }
			]
		},
		"query": {
			"sql":"select id, name, amount, duedate FROM requestv1 WHERE id=:id;",
			"parameters":[
				{ "placeholder": ":id", "getparameter": "id" }
			]
		},
		"form": {
			"title": "My editing form",
			"submitTitle": "Save",
			"fields": [
				{ "type":"textarea", "name":"name", "label":"Name", "placeholder":"Name", "sqlfield":"name", "width":"6", "row":"1" },
				{ "type":"currency", "name":"amount", "label":"Amount", "placeholder":"10.0", "sqlfield":"amount", "width":"6", "row":"1" },
				{ "type":"date", "name":"duedate", "label":"Due date", "sqlfield":"duedate", "width":"6", "row":"2" },
				{ "type":"dropdown", "name":"category", "label":"Category", "sqlfield":"category", "width":"6", "row":"2", "options":[
					{ "value":"High", "Label":"High" },
					{ "value":"Medium", "Label":"Medium" },
					{ "value":"Low", "Label":"Low" }
				]},
				{ "type":"hidden", "name":"id", "sqlfield":"id", "row":"2" }
			]
		}
	},
	"post": {
		"request": {
			"postparameters": [
				{ "validation":"required|numeric", "name":"id" },
				{ "validation":"max_len,2500", "name":"name" },
				{ "validation":"required|numeric", "name":"amount" },
				{ "validation":"max_len,10", "name":"duedate" }
			]
		},
		"transactions": [
			{
				"sql":"UPDATE requestv1 SET name=:name, amount=:amount, duedate=:duedate WHERE id=:id;",
				"parameters":[
					{ "placeholder": ":id", "postparameter": "id" },
					{ "placeholder": ":name", "postparameter": "name" },
					{ "placeholder": ":amount", "postparameter": "amount" },
					{ "placeholder": ":duedate", "postparameter": "duedate" }
				]
			}
		]
	}
}');
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
        $router = $this->getMockBuilder(Firststep\Common\Router\Router::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
        $dbconnection = $this->getMockBuilder(Firststep\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock();
        $queryExecuter = $this->getMockBuilder(Firststep\Common\Database\QueryExecuter::class)->getMock();
		$queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { return new stdClass; }}));
        $queryBuilder = $this->getMockBuilder(Firststep\Common\Builders\QueryBuilder::class)->getMock();

        $form->setRouter($router);
        $form->setParameters( array( 'id' => '1' ) );
		$form->setResource( $this->form );
		$form->setDbconnection( $dbconnection );
        $form->setQueryExecuter( $queryExecuter );
        $form->setQueryBuilder( $queryBuilder );
		$block = $form->createForm();
		$this->assertContains('<textarea class="form-control" id="name" name="name"></textarea>', $block->show());
		unset($form);
	}
	
	public function testFormContainsCurrencyField(){
		$form = new Firststep\Common\Builders\FormBuilder;
		$router = $this->getMockBuilder(Firststep\Common\Router\Router::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
        $dbconnection = $this->getMockBuilder(Firststep\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock();
        $queryExecuter = $this->getMockBuilder(Firststep\Common\Database\QueryExecuter::class)->getMock();
		$queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { return new stdClass; }}));
        $queryBuilder = $this->getMockBuilder(Firststep\Common\Builders\QueryBuilder::class)->getMock();

        $form->setRouter($router);
        $form->setParameters( array( 'id' => '1' ) );
		$form->setResource( $this->form );
		$form->setDbconnection( $dbconnection );
        $form->setQueryExecuter( $queryExecuter );
        $form->setQueryBuilder( $queryBuilder );
		$block = $form->createForm();
		$this->assertContains('<input class="form-control" type="number" id="amount" name="amount" value="" placeholder="10.0" min="0" step="0.01">', $block->show());
		unset($form);
	}
	
	public function testFormContainsDateField(){
		$form = new Firststep\Common\Builders\FormBuilder;
$router = $this->getMockBuilder(Firststep\Common\Router\Router::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
        $dbconnection = $this->getMockBuilder(Firststep\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock();
        $queryExecuter = $this->getMockBuilder(Firststep\Common\Database\QueryExecuter::class)->getMock();
		$queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { return new stdClass; }}));
        $queryBuilder = $this->getMockBuilder(Firststep\Common\Builders\QueryBuilder::class)->getMock();

        $form->setRouter($router);
        $form->setParameters( array( 'id' => '1' ) );
		$form->setResource( $this->form );
		$form->setDbconnection( $dbconnection );
        $form->setQueryExecuter( $queryExecuter );
        $form->setQueryBuilder( $queryBuilder );
		$block = $form->createForm();
		$this->assertContains('<input class="form-control" type="date" id="duedate" name="duedate" value="" >', $block->show());
		unset($form);
	}
	
	public function testFormContainsTextAreaWithData(){
		$form = new Firststep\Common\Builders\FormBuilder;
$router = $this->getMockBuilder(Firststep\Common\Router\Router::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
        $dbconnection = $this->getMockBuilder(Firststep\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock();
        $queryExecuter = $this->getMockBuilder(Firststep\Common\Database\QueryExecuter::class)->getMock();
		$queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { $e = new stdClass; $e->name = 'prova'; return $e; }}));
        $queryBuilder = $this->getMockBuilder(Firststep\Common\Builders\QueryBuilder::class)->getMock();

        $form->setRouter($router);
        $form->setParameters( array( 'id' => '1' ) );
		$form->setResource( $this->form );
		$form->setDbconnection( $dbconnection );
        $form->setQueryExecuter( $queryExecuter );
        $form->setQueryBuilder( $queryBuilder );
		$block = $form->createForm();
		$this->assertContains('name="name">prova</textarea>', $block->show());
		unset($form);
	}
	
	public function testFormContainsCurrencyFieldWithData(){
		$form = new Firststep\Common\Builders\FormBuilder;
$router = $this->getMockBuilder(Firststep\Common\Router\Router::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
        $dbconnection = $this->getMockBuilder(Firststep\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock();
        $queryExecuter = $this->getMockBuilder(Firststep\Common\Database\QueryExecuter::class)->getMock();
		$queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { $e = new stdClass; $e->amount = 10; return $e; }}));
        $queryBuilder = $this->getMockBuilder(Firststep\Common\Builders\QueryBuilder::class)->getMock();

        $form->setRouter($router);
        $form->setParameters( array( 'id' => '1' ) );
		$form->setResource( $this->form );
		$form->setDbconnection( $dbconnection );
        $form->setQueryExecuter( $queryExecuter );
        $form->setQueryBuilder( $queryBuilder );
		$block = $form->createForm();
		$this->assertContains('name="amount" value="10" placeholder="10.0"', $block->show());
		unset($form);
	}
	
	public function testFormContainsDateFieldWithData(){
		$form = new Firststep\Common\Builders\FormBuilder;
$router = $this->getMockBuilder(Firststep\Common\Router\Router::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
        $dbconnection = $this->getMockBuilder(Firststep\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock();
        $queryExecuter = $this->getMockBuilder(Firststep\Common\Database\QueryExecuter::class)->getMock();
		$queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { $e = new stdClass; $e->duedate = '26/06/2017'; return $e; }}));
        $queryBuilder = $this->getMockBuilder(Firststep\Common\Builders\QueryBuilder::class)->getMock();

        $form->setRouter($router);
        $form->setParameters( array( 'id' => '1' ) );
		$form->setResource( $this->form );
		$form->setDbconnection( $dbconnection );
        $form->setQueryExecuter( $queryExecuter );
        $form->setQueryBuilder( $queryBuilder );
		$block = $form->createForm();
		$this->assertContains('name="duedate" value="26/06/2017"', $block->show());
		unset($form);
	}

}
