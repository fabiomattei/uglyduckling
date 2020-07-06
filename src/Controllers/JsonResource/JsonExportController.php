<?php

namespace Fabiom\UglyDuckling\Controllers\JsonResource;

use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Excel\ExcelJsonTemplate;
use Fabiom\UglyDuckling\Common\Controllers\JsonResourceBasicController;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Router\ResourceRouter;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\QueryBuilder;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Form\FormJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Pdf\PdfJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Menu\MenuJsonTemplate;

/**
 * User: Fabio
 * Date: 11/09/2018
 * Time: 22:34
 */
class JsonExportController extends JsonResourceBasicController {

    private $formBuilder;
    private $pdfBuilder;
    private $menubuilder;
    private $excelBuilder;

    function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
		$this->formBuilder = new FormJsonTemplate;
		$this->pdfBuilder = new PdfJsonTemplate;
        $this->excelBuilder = new ExcelJsonTemplate;
		$this->menubuilder = new MenuJsonTemplate;
    }
	
    /**
     * @throws GeneralException
     */
	public function getRequest() {
        $this->formBuilder->setResource( $this->resource );
        $this->formBuilder->setAction($this->routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_EXPORT, 'res='.$this->getParameters['res'] ));
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Office export';

		$menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->routerContainer );
        $this->menubuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );
	
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array();
		$this->centralcontainer = array( $this->formBuilder->createForm() );
	}

    public function postRequest() {
        $this->templateFile = $this->setup->getEmptyTemplateFileName();
	    if ($this->resource->documenttype == 'pdf' ) {
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
