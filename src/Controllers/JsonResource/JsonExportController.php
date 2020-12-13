<?php

namespace Fabiom\UglyDuckling\Controllers\JsonResource;

use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Excel\ExcelJsonTemplate;
use Fabiom\UglyDuckling\Common\Controllers\JsonResourceBasicController;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Pdf\PdfJsonTemplate;

/**
 * User: Fabio
 * Date: 11/09/2018
 * Time: 22:34
 */
class JsonExportController extends JsonResourceBasicController {

    protected $pdfBuilder;
    protected $excelBuilder;

    public function getRequest() {
        $this->templateFile = $this->applicationBuilder->getSetup()->getEmptyTemplateFileName();
	    if ($this->resource->documenttype == 'pdf' ) {
            $this->pdfBuilder = new PdfJsonTemplate( $this->applicationBuilder, $this->pageStatus );
            $this->pdfBuilder->setResource( $this->resource );
            $this->pdfBuilder->setParameters( $this->postParameters );
            $this->pdfBuilder->setDbconnection( $this->dbconnection );
            $this->pdfBuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );

            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($this->pdfBuilder->createTable());
            $mpdf->Output();
            //$mpdf->Output( $this->resource->filename.'.pdf', 'D');
            exit;
        } else {
            $this->excelBuilder = new ExcelJsonTemplate( $this->applicationBuilder, $this->pageStatus );
            $this->excelBuilder->setResource( $this->resource );
            $this->excelBuilder->setParameters( $this->postParameters );
            $this->excelBuilder->setDbconnection( $this->dbconnection );

            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$this->resource->filename.'.xlsx"');

	        $writer = $this->excelBuilder->getWriter();
            // $writer->save($this->resource->filename.'.xlsx');
            $writer->save("php://output");
        }
	}

	// Overriding method in order to avoid any output
    public function getInfo(): string {
        return '';
    }

}
