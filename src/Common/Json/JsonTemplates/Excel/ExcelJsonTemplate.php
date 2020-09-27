<?php

/**
 * Created by Fabio Mattei
 * Date: 02/11/2018
 * Time: 11:48
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Excel;

use Fabiom\UglyDuckling\Common\Database\DBConnection;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelJsonTemplate {

    private QueryExecuter $queryExecuter;
    private DBConnection $dbconnection;
    private array $parameters;
    private $resource;

    function __construct() {
        $this->queryExecuter = new QueryExecuter;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters( $parameters ) {
        $this->parameters = $parameters;
    }

    /**
     * @param mixed $resource
     */
    public function setResource( $resource ) {
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
        $this->queryExecuter->setQueryStructure( $this->resource->post->query );
        if (isset( $this->parameters ) ) $this->queryExecuter->setPostParameters( $this->parameters );
        $entities = $this->queryExecuter->executeSql();

        $table = $this->resource->post->table;

        $spreadsheet = new Spreadsheet();

        // Add titles
        $col = 1;
        foreach ($table->fields as $field) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col,1, $field->headline);
            $col++;
        }

        $row = 1;
        foreach ($entities as $entity) {
            $col = 1;
            foreach ($table->fields as $field) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col,1, $entity->{$field->sqlfield});
                $col++;
            }
            $row++;
        }

        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle($this->resource->filename);

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);

        return $writer;
    }

}