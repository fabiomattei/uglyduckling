<?php

/**
*  Testing the InfoBuilder class
*
*  @author Fabio Mattei
*/
class InfoJsonTemplateTest extends PHPUnit_Framework_TestCase {
	
    private $info;
	private $entity;
	private $htmlTemplateLoader;
	private $queryExecuter;
	private $infoBlock;
	
	protected function setUp() {
        $this->htmlTemplateLoader = new \Firststep\Common\Utils\HtmlTemplateLoader();
        $this->htmlTemplateLoader->setPath( 'src/Templates/HTML/' );
        $this->info = new Firststep\Common\Json\JsonTemplates\Info\InfoJsonTemplate();
        $this->info->setHtmlTemplateLoader($this->htmlTemplateLoader);

		$this->entity = new stdClass;
	    $this->entity->fl_id   = 3;	
	    $this->entity->fl_name = 'prova';
		$this->entity->fl_amount = 10;
		$this->entity->fl_duedate = '2017-06-26';
		
		$this->infoBlock = json_decode('{ 
  "name": "inforequestv1",
  "metadata": { "type":"info", "version": "1" },
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
    "info": {
      "title": "My new info panel",
      "fields": [
        { "type":"textarea", "validation":"max_len,2500", "name":"name", "label":"Name", "placeholder":"Name", "value":"name", "width":"6", "row":"1" },
        { "type":"currency", "validation":"required|numeric", "name":"amount", "label":"Amount", "placeholder":"10.0", "value":"amount", "width":"6", "row":"1" },
        { "type":"date", "validation":"max_len,10", "name":"duedate", "label":"Due date", "value":"duedate", "width":"12", "row":"2" },
        { "type":"hidden", "validation":"required|numeric", "name":"id", "value":"id" }
      ]
    }
  }
}');

        $router = $this->getMockBuilder(Firststep\Common\Router\Router::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
        $dbconnection = $this->getMockBuilder(Firststep\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock();
        $this->queryExecuter = $this->getMockBuilder(Firststep\Common\Database\QueryExecuter::class)->getMock();
        $queryBuilder = $this->getMockBuilder(Firststep\Common\Json\JsonTemplates\QueryBuilder::class)->getMock();

        $this->info->setRouter($router);
        $this->info->setParameters( array( 'id' => '1' ) );
        $this->info->setResource( $this->infoBlock );
        $this->info->setDbconnection( $dbconnection );
        $this->info->setQueryExecuter( $this->queryExecuter );
        $this->info->setQueryBuilder( $queryBuilder );
	}
	
	/**
	* Just check if the YourClass has no syntax error 
	*
	* This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
	* any typo before you even use this library in a real project.
	*
	*/
	public function testIsThereAnySyntaxError(){
		$this->assertTrue(is_object($this->info));
		unset($this->info);
	}
	
	public function testInfoContainsTextArea(){
        $this->queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { $e = new stdClass; $e->name = 'prova'; return $e; }}));

		$block = $this->info->createInfo();
		$this->assertContains('<p>prova</p>', $block->show() );
		unset($this->info);
	}
	
	public function testInfoContainsCurrencyFieldWithData(){
        $this->queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { $e = new stdClass; $e->amount = 10; return $e; }}));

		$block = $this->info->createInfo();
		$this->assertContains('<p>10</p>', $block->show() );
		unset($this->info);
	}
	
	public function testInfoContainsDateFieldWithData(){
        $this->queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { $e = new stdClass; $e->duedate = '2017-06-26'; return $e; }}));

		$block = $this->info->createInfo();
		$this->assertContains('<p>26/06/2017</p>', $block->show() );
		unset($this->info);
	}

}
