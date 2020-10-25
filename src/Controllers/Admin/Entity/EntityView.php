<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\Entity;

use Fabiom\UglyDuckling\Common\Controllers\AdminController;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLInfo;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Blocks\Button;
use Fabiom\UglyDuckling\Common\Router\ResourceRouter;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;

/**
 * 
 */
class EntityView extends AdminController {

	function __construct() {
		$this->queryExecuter = new QueryExecuter;
    }
	
    public $get_validation_rules = array( 'res' => 'required|max_len,50' );
    public $get_filter_rules     = array( 'res' => 'trim' );
	
    /**
     * @throws GeneralException
     *
     * $this->getParameters['res'] resource key index
     */
	public function getRequest() {
		$this->queryExecuter->setDBH( $this->applicationBuilder->getDbconnection()->getDBH() );
		$this->resource = $this->applicationBuilder->getJsonloader()->loadResource( $this->getParameters['res'] );
		
		$this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Admin entity view';
		
		$info = new BaseHTMLInfo;
        $info->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
		$info->setTitle( 'Entity name: '.$this->resource->name );
		$info->addParagraph( 'Database table name: '.$this->resource->entity->tablename, '' );

		$tableExists = $this->queryExecuter->executeTableExists( $this->queryExecuter->tableExists($this->resource->entity->tablename) );
			
		$info->addUnfilteredParagraph( 'Table exists: '.( $tableExists ? 
			'true  '.Button::get($this->applicationBuilder->getRouterContainer()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_ENTITY_DROP_TABLE, 'res='.$this->resource->name ), 'Drop', Button::COLOR_GRAY.' '.Button::SMALL ) :
			'false  '.Button::get($this->applicationBuilder->getRouterContainer()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_ENTITY_CREATE_TABLE, 'res='.$this->resource->name ), 'Create', Button::COLOR_GRAY.' '.Button::SMALL )
		), '' );

        $resourcesTable = new StaticTable;
        $resourcesTable->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $resourcesTable->setTitle("Called from resources");
        $resourcesTable->addTHead();
        $resourcesTable->addRow();
        $resourcesTable->addHeadLineColumn('Name');
        $resourcesTable->addHeadLineColumn('Type');
        $resourcesTable->addHeadLineColumn('SQL');
        $resourcesTable->closeRow();
        $resourcesTable->closeTHead();
        $resourcesTable->addTBody();
        foreach ( $this->applicationBuilder->getJsonloader()->getResourcesIndex() as $reskey => $resvalue ) {
            $tmpres = $this->applicationBuilder->getJsonloader()->loadResource( $reskey );
            if ( isset($tmpres->get->query) AND strpos($tmpres->get->query->sql, $this->resource->entity->tablename) !== false )
            {
                $resourcesTable->addRow();
                $resourcesTable->addColumn($reskey);
                $resourcesTable->addColumn($tmpres->metadata->type);
                $resourcesTable->addColumn($tmpres->get->query->sql);
                $resourcesTable->closeRow();
            }
            if ( isset($tmpres->post->query) AND strpos($tmpres->post->query->sql, $this->resource->entity->tablename) !== false )
            {
                $resourcesTable->addRow();
                $resourcesTable->addColumn($reskey);
                $resourcesTable->addColumn($tmpres->metadata->type);
                $resourcesTable->addColumn($tmpres->post->query->sql);
                $resourcesTable->closeRow();
            }
            if (isset($tmpres->get->transactions)) {
                foreach ( $tmpres->get->transactions as $transaction ) {
                    if ( isset($transaction->sql) AND strpos($transaction->sql, $this->resource->entity->tablename) !== false )
                    {
                        $resourcesTable->addRow();
                        $resourcesTable->addColumn($reskey);
                        $resourcesTable->addColumn($tmpres->metadata->type);
                        $resourcesTable->addColumn($transaction->sql);
                        $resourcesTable->closeRow();
                    }
                }
            }
            if (isset($tmpres->post->transactions)) {
                foreach ( $tmpres->post->transactions as $transaction ) {
                    if ( isset($transaction->sql) AND strpos($transaction->sql, $this->resource->entity->tablename) !== false )
                    {
                        $resourcesTable->addRow();
                        $resourcesTable->addColumn($reskey);
                        $resourcesTable->addColumn($tmpres->metadata->type);
                        $resourcesTable->addColumn($transaction->sql);
                        $resourcesTable->closeRow();
                    }
                }
            }
        }
        $resourcesTable->closeTBody();
		
		$this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_ENTITY_LIST ) );
		$this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_ENTITY_LIST, $this->applicationBuilder->getRouterContainer() ) );
		$this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array( $resourcesTable );
        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
	}

}
