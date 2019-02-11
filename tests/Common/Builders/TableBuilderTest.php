<?php

/**
*  Testing the TableBuilder class
*
*  @author Fabio Mattei
*/
class TableBuilderTest extends PHPUnit_Framework_TestCase {
	
    protected $table;
	protected $entity;
	protected $htmlTemplateLoader;
	protected $queryExecuter;
	
	protected function setUp() {
        $this->htmlTemplateLoader = new \Firststep\Common\Utils\HtmlTemplateLoader();
        $this->htmlTemplateLoader->setPath( 'src/Templates/HTML/' );
        $this->tableBuilder = new Firststep\Common\Builders\TableBuilder;
        $this->tableBuilder->setHtmlTemplateLoader($this->htmlTemplateLoader);

		$this->entity = new stdClass;
	    $this->entity->fl_id   = 3;	
	    $this->entity->fl_name = 'prova';
		$this->entity->fl_amount = 10;
		$this->entity->fl_duedate = '2017-06-26';

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
        $queryBuilder = $this->getMockBuilder(Firststep\Common\Builders\QueryBuilder::class)->getMock();

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
	public function testIsThereAnySyntaxError(){
		$this->assertTrue(is_object($this->tableBuilder));
		unset($this->tableBuilder);
	}

}
