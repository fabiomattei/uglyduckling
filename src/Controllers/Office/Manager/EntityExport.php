<?php

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Controllers\ManagerEntityController;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Builders\QueryBuilder;
use Firststep\Common\Builders\FormBuilder;
use Firststep\Common\Builders\PDFBuilder;
use Firststep\Common\Builders\MenuBuilder;

/**
 * User: Fabio
 * Date: 11/09/2018
 * Time: 22:34
 */
class EntityExport extends ManagerEntityController {

    private $formBuilder;
    private $pdfBuilder;
    private $menubuilder;

    function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
		$this->formBuilder = new FormBuilder;
		$this->pdfBuilder = new PDFBuilder;
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
		$this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->router ) );
		$this->centralcontainer = array( $this->formBuilder->createForm() );
	}

    public function postRequest() {
        $this->pdfBuilder->setResource( $this->resource );
        $this->pdfBuilder->setParameters( $this->postParameters );
        $this->pdfBuilder->setDbconnection( $this->dbconnection );

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($this->pdfBuilder->createTable());
        $mpdf->Output();
        //$mpdf->Output( 'activitites.pdf', 'D');
        exit;
	}

}
