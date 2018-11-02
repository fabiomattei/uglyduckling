<?php

/**
 * Created by Fabio Mattei
 * Date: 02/11/2018
 * Time: 11:48
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Database\QueryExecuter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelBuilder {

    private $queryExecuter;
    private $queryBuilder;
    private $resource;
    private $parameters;

    function __construct() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters) {
        $this->parameters = $parameters;
    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource) {
        $this->resource = $resource;
    }

    /**
     * @param mixed $dbconnection
     */
    public function setDbconnection($dbconnection) {
        $this->dbconnection = $dbconnection;
    }

    public function getWriter() {
        $this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
        $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
        $this->queryExecuter->setQueryStructure( $this->resource->post->query );
        if (isset( $this->parameters ) ) $this->queryExecuter->setPostParameters( $this->parameters );
        $entities = $this->queryExecuter->executeQuery();

        $table = $this->resource->post->table;

        //$tableBlock = new BaseTable;
        //$tableBlock->setTitle($table->title ?? '');
//
        //$tableBlock->addTHead();
        //$tableBlock->addRow();
        //foreach ($table->fields as $field) {
        //    $tableBlock->addHeadLineColumn($field->headline);
        //}
        //$tableBlock->closeRow();
        //$tableBlock->closeTHead();
//
        //$tableBlock->addTBody();
        //foreach ($entities as $entity) {
        //    $tableBlock->addRow();
        //    foreach ($table->fields as $field) {
        //        $tableBlock->addColumn($entity->{$field->sqlfield});
        //    }
        //    $tableBlock->closeRow();
        //}
        //$tableBlock->closeTBody();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');

        $writer = new Xlsx($spreadsheet);
        $writer->save('hello world.xlsx');

        return $writer;
    }

}