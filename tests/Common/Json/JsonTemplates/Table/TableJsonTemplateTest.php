<?php

/**
*  Testing the TableBuilder class
*
*  @author Fabio Mattei
*/
class TableJsonTemplateTest extends PHPUnit_Framework_TestCase {
	
	protected $entities;
	protected $htmlTemplateLoader;
	protected $queryExecuter;
    protected $tableBuilder;
	
	protected function setUp() {
        $this->htmlTemplateLoader = new \Firststep\Common\Utils\HtmlTemplateLoader();
        $this->htmlTemplateLoader->setPath( 'src/Templates/HTML/' );
        $this->tableBuilder = new Firststep\Common\Json\JsonTemplates\Table\TableJsonTemplate;
        $this->tableBuilder->setHtmlTemplateLoader($this->htmlTemplateLoader);

		$entity = new stdClass;
	    $entity->id   = 3;
	    $entity->name = 'prova';
		$entity->amount = 10;
		$entity->duedate = '2017-06-26';
        $entity2 = new stdClass;
        $entity2->id   = 3;
        $entity2->name = 'prova';
        $entity2->amount = 10;
        $entity2->duedate = '2017-06-26';

        $this->entities = array();
        $this->entities[] = $entity;
        $this->entities[] = $entity2;

		$this->jsonform = json_decode('{
  "name": "requesttablev1",
  "metadata": { "type":"table", "version": "1" },
  "allowedgroups": [ "administrationgroup", "teachergroup", "managergroup" ],
  "get": {
    "request": {
      "parameters": []
    },
    "query": {
      "sql": "select id, name, amount, duedate FROM requestv1;"
    },
    "table": {
      "title": "My table",
      "fields": [
        {"headline": "Name", "sqlfield": "name"},
        {"headline": "Amount", "sqlfield": "amount"},
        {"headline": "Due date", "sqlfield": "duedate"}
      ],
      "actions": [
        {"label": "Info", "action": "entityinfo", "resource": "inforequestv1", "parameters":[{"name": "id", "sqlfield": "id"}] },
        {"label": "Edit", "action": "entityform", "resource": "formrequestv1", "parameters":[{"name": "id", "sqlfield": "id"}] },
        {"label": "Delete", "action": "entitytransaction", "resource": "deletereportv1", "parameters":[{"name": "id", "sqlfield": "id"}] },
        {"label": "Search", "action": "entitysearch", "resource": "searchreportv1", "parameters":[{}] },
        {"label": "Export", "action": "entityexport", "resource": "requestexportv1", "parameters":[{}] }
      ]
    }
  }
}');

        $router = $this->getMockBuilder(Firststep\Common\Router\Router::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
        $dbconnection = $this->getMockBuilder(Firststep\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock();
        $this->queryExecuter = $this->getMockBuilder(Firststep\Common\Database\QueryExecuter::class)->getMock();
        $queryBuilder = $this->getMockBuilder(Firststep\Common\Json\JsonTemplates\QueryBuilder::class)->getMock();

        $this->tableBuilder->setRouter($router);
        $this->tableBuilder->setParameters( array( 'id' => '1' ) );
        $this->tableBuilder->setResource( $this->jsonform );
        $this->tableBuilder->setDbconnection( $dbconnection );
        $this->tableBuilder->setQueryExecuter( $this->queryExecuter );
        $this->tableBuilder->setQueryBuilder( $queryBuilder );
	}
	
	/**
	* Just check if the YourClass has no syntax error 
	*
	* This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
	* any typo before you even use this library in a real project.
	*
	*/
	public function testIsThereAnySyntaxError() {
		$this->assertTrue(is_object($this->tableBuilder));
		unset($this->tableBuilder);
	}

    public function testTableContainsTableTag() {
        $this->queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue( $this->entities ));
        $block = $this->tableBuilder->createTable();
        $this->assertContains('<table', $block->show());
        unset($this->tableBuilder);
    }

    public function testTableContainsColumnsTitles() {
        $this->queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue( $this->entities ));
        $block = $this->tableBuilder->createTable();
        $this->assertContains('<th>Name</th>', $block->show());
        $this->assertContains('<th>Amount</th>', $block->show());
        $this->assertContains('<th>Due date</th>', $block->show());
        unset($this->tableBuilder);
    }

    public function testTableContainsColumnsFirstEntity() {
        $this->queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue( $this->entities ));
        $block = $this->tableBuilder->createTable();
        $this->assertContains('<td>prova</td>', $block->show());
        $this->assertContains('<td>10</td>', $block->show());
        $this->assertContains('<td>2017-06-26</td>', $block->show());
        unset($this->tableBuilder);
    }

}
