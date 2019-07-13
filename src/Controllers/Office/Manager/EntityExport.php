<?php

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Json\JsonTemplates\Excel\ExcelJsonTemplate;
use Firststep\Common\Controllers\ManagerEntityController;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Json\JsonTemplates\QueryBuilder;
use Firststep\Common\Json\JsonTemplates\Form\FormJsonTemplate;
use Firststep\Common\Json\JsonTemplates\Pdf\PdfJsonTemplate;
use Firststep\Common\Json\JsonTemplates\MenuBuilder;

/**
 * User: Fabio
 * Date: 11/09/2018
 * Time: 22:34
 */
class EntityExport extends ManagerEntityController {

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
		$this->menubuilder = new MenuBuilder;
    }
	
    /**
     * @throws GeneralException
     */
	public function getRequest() {
        $this->formBuilder->setResource( $this->resource );
        $this->formBuilder->setAction($this->router->make_url( Router::ROUTE_OFFICE_ENTITY_EXPORT, 'res='.$this->getParameters['res'] ));
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Office export';

		$menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->router );
	
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
