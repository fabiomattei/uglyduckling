<?php

/**
*  Testing the FormBuilder class
*
*  @author Fabio Mattei
*/
class FormBuilderTest extends PHPUnit_Framework_TestCase {
	
    protected $form;
	protected $entity;
	protected $htmlTemplateLoader;
	protected $queryExecuter;
	
	protected function setUp() {
        $this->htmlTemplateLoader = new \Firststep\Common\Utils\HtmlTemplateLoader();
        $this->htmlTemplateLoader->setPath( 'src/Templates/HTML/' );
        $this->form = new Firststep\Common\Json\TemplateBuilders\FormBuilder;
        $this->form->setHtmlTemplateLoader($this->htmlTemplateLoader);

		$this->entity = new stdClass;
	    $this->entity->fl_id   = 3;	
	    $this->entity->fl_name = 'prova';
		$this->entity->fl_amount = 10;
		$this->entity->fl_duedate = '2017-06-26';

		$this->jsonform = json_decode('{ 
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
				{ "type":"date", "name":"duedate", "label":"Due date", "placeholder":"2019-02-22", "sqlfield":"duedate", "width":"6", "row":"2" },
				{ "type":"dropdown", "name":"category", "label":"Category", "sqlfield":"category", "width":"6", "row":"2", "options":[
					{ "value":"High", "label":"High" },
					{ "value":"Medium", "label":"Medium" },
					{ "value":"Low", "label":"Low" }
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

        $router = $this->getMockBuilder(Firststep\Common\Router\Router::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
        $dbconnection = $this->getMockBuilder(Firststep\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock();
        $this->queryExecuter = $this->getMockBuilder(Firststep\Common\Database\QueryExecuter::class)->getMock();
        $queryBuilder = $this->getMockBuilder(Firststep\Common\Json\TemplateBuilders\QueryBuilder::class)->getMock();

        $this->form->setRouter($router);
        $this->form->setParameters( array( 'id' => '1' ) );
        $this->form->setResource( $this->jsonform );
        $this->form->setDbconnection( $dbconnection );
        $this->form->setQueryExecuter( $this->queryExecuter );
        $this->form->setQueryBuilder( $queryBuilder );
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

    public function testFormContainsFormTag(){
        $this->queryExecuter->expects($this->any())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { return new stdClass; }}));
        $block = $this->form->createForm();
        $this->assertContains('<form', $block->show());
        unset($this->form);
    }
	
	public function testFormContainsTextArea(){
        $this->queryExecuter->expects($this->any())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { return new stdClass; }}));
        $block = $this->form->createForm();
		$this->assertContains('<textarea class="form-control" id="name" name="name"></textarea>', $block->show());
		unset($this->form);
	}
	
	public function testFormContainsCurrencyField(){
        $this->queryExecuter->expects($this->any())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { return new stdClass; }}));
        $block = $this->form->createForm();
		$this->assertContains('<input class="form-control" type="number" id="amount" name="amount" value="" placeholder="10.0" min="0" step="0.01">', $block->show());
		unset($this->form);
	}
	
	public function testFormContainsDateField(){
        $this->queryExecuter->expects($this->any())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { return new stdClass; }}));
        $block = $this->form->createForm();
		$this->assertContains('<input class="form-control" type="date" id="duedate" name="duedate" value="" placeholder="2019-02-22">', $block->show());
		unset($this->form);
	}
	
	public function testFormContainsTextAreaWithData(){
        $this->queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { $e = new stdClass; $e->name = 'prova'; return $e; }}));
		$block = $this->form->createForm();
		$this->assertContains('name="name">prova</textarea>', $block->show());
		unset($this->form);
	}
	
	public function testFormContainsCurrencyFieldWithData(){
        $this->queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { $e = new stdClass; $e->amount = 10; return $e; }}));
		$block = $this->form->createForm();
		$this->assertContains('name="amount" value="10" placeholder="10.0"', $block->show());
		unset($this->form);
	}
	
	public function testFormContainsDateFieldWithData(){
        $this->queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { $e = new stdClass; $e->duedate = '26/06/2017'; return $e; }}));
		$block = $this->form->createForm();
		$this->assertContains('name="duedate" value="26/06/2017"', $block->show());
		unset($this->form);
	}

}
