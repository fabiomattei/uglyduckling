<?php

/**
 * Created by Fabio Mattei
 * Date: 02/11/2018
 * Time: 11:48
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Excel;

use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelJsonTemplate extends JsonTemplate {

    protected $columnNames array( 1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L', 13 => 'M', 14 => 'N', 15 => 'O', 16 => 'P', 17 => 'Q', 18 => 'R', 19 => 'S', 20 => 'T', 21 => 'U', 22 => 'V', 23 => 'W', 24 => 'X', 25 => 'Y', 26 => 'Z', 27 => 'AA', 28 => 'AB', 29 => 'AC', 30 => 'AD', 31 => 'AE', 32 => 'AF', 33 => 'AG', 34 => 'AH', 35 => 'AI', 36 => 'AJ', 37 => 'AK', 38 => 'AL', 39 => 'AM', 40 => 'AN' );

    /**
     * ExcelJsonTemplate constructor.
     * @param $applicationBuilder
     * @param $pageStatus
     */
    function __construct( $applicationBuilder, $pageStatus ) {
        parent::__construct( $applicationBuilder, $pageStatus);
    }

    public function getWriter() {
        $queryExecutor = $this->pageStatus->getQueryExecutor();

        // If there are dummy data they take precedence in order to fill the table
        if ( isset($this->resource->get->dummydata) ) {
            $entities = $this->resource->get->dummydata;
        } else {
            // If there is a query I look for data to fill the table,
            // if there is not query I do not
            if ( isset($this->resource->get->query) ) {
                $queryExecutor->setResourceName( $this->resource->name ?? 'undefined ');
                $queryExecutor->setQueryStructure( $this->resource->get->query );
                $entities = $queryExecutor->executeSql();
            }
        }

        $table = $this->resource->get->table;

        $spreadsheet = new Spreadsheet();

        // Add titles
        $col = 1;
        foreach ($table->fields as $field) {
            $spreadsheet->setActiveSheetIndex(0)->getColumnDimension($this->columnNames[$col])->setWidth($field->width ?? 20);
            $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col,1, $field->headline);
            $col++;
        }

        $row = 2;
        foreach ($entities as $entity) {
            $this->pageStatus->setLastEntity($entity);
            $col = 1;
            foreach ($table->fields as $field) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col, $row, $this->pageStatus->getValue($field));
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