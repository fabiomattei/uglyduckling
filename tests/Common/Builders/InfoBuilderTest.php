<?php

/**
*  Testing the InfoBuilder class
*
*  @author Fabio Mattei
*/
class InfoBuilderTest extends PHPUnit_Framework_TestCase {
	
    private $info;
	private $entity;
	
	protected function setUp() {
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
	}
	
	/**
	* Just check if the YourClass has no syntax error 
	*
	* This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
	* any typo before you even use this library in a real project.
	*
	*/
	public function testIsThereAnySyntaxError(){
		$info = new Firststep\Common\Builders\InfoBuilder;
		$this->assertTrue(is_object($info));
		unset($info);
	}
	
	public function testInfoContainsTextArea(){
		$info = new Firststep\Common\Builders\InfoBuilder;
		$router = $this->getMockBuilder(Firststep\Common\Router\Router::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
        $dbconnection = $this->getMockBuilder(Firststep\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock();
        $queryExecuter = $this->getMockBuilder(Firststep\Common\Database\QueryExecuter::class)->getMock();
		$queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { $e = new stdClass; $e->name = 'prova'; return $e; }}));
        $queryBuilder = $this->getMockBuilder(Firststep\Common\Builders\QueryBuilder::class)->getMock();

        $info->setRouter($router);
        $info->setParameters( array( 'id' => '1' ) );
		$info->setResource( $this->infoBlock );
		$info->setDbconnection( $dbconnection );
        $info->setQueryExecuter( $queryExecuter );
        $info->setQueryBuilder( $queryBuilder );
		$block = $info->createInfo();
		$this->assertContains('<p>prova</p>', $block->show() );
		unset($info);
	}
	
	public function testInfoContainsCurrencyFieldWithData(){
		$info = new Firststep\Common\Builders\InfoBuilder;
		$router = $this->getMockBuilder(Firststep\Common\Router\Router::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
        $dbconnection = $this->getMockBuilder(Firststep\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock();
        $queryExecuter = $this->getMockBuilder(Firststep\Common\Database\QueryExecuter::class)->getMock();
		$queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { $e = new stdClass; $e->amount = 10; return $e; }}));
        $queryBuilder = $this->getMockBuilder(Firststep\Common\Builders\QueryBuilder::class)->getMock();

        $info->setRouter($router);
        $info->setParameters( array( 'id' => '1' ) );
		$info->setResource( $this->infoBlock );
		$info->setDbconnection( $dbconnection );
        $info->setQueryExecuter( $queryExecuter );
        $info->setQueryBuilder( $queryBuilder );
		$block = $info->createInfo();
		$this->assertContains('<p>10</p>', $block->show() );
		unset($info);
	}
	
	public function testInfoContainsDateFieldWithData(){
		$info = new Firststep\Common\Builders\InfoBuilder;
		$router = $this->getMockBuilder(Firststep\Common\Router\Router::class)->setConstructorArgs( array('http://localhost:18080/') )->getMock();
        $dbconnection = $this->getMockBuilder(Firststep\Common\Database\DBConnection::class)->setConstructorArgs( array('', '', '', ''))->getMock();
        $queryExecuter = $this->getMockBuilder(Firststep\Common\Database\QueryExecuter::class)->getMock();
		$queryExecuter->expects($this->once())->method('executeQuery')->will($this->returnValue(new class { public function fetch() { $e = new stdClass; $e->duedate = '2017-06-26'; return $e; }}));
        $queryBuilder = $this->getMockBuilder(Firststep\Common\Builders\QueryBuilder::class)->getMock();

        $info->setRouter($router);
        $info->setParameters( array( 'id' => '1' ) );
		$info->setResource( $this->infoBlock );
		$info->setDbconnection( $dbconnection );
        $info->setQueryExecuter( $queryExecuter );
        $info->setQueryBuilder( $queryBuilder );
		$block = $info->createInfo();
		$this->assertContains('<p>26/06/2017</p>', $block->show() );
		unset($info);
	}

}
