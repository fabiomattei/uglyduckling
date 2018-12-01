<?php

namespace Firststep\Controllers\Admin\Entity;

use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Blocks\BaseInfo;
use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Blocks\Button;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Builders\QueryBuilder;

/**
 * 
 */
class EntityView extends Controller {

	function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
    }
	
    public $get_validation_rules = array( 'res' => 'required|max_len,50' );
    public $get_filter_rules     = array( 'res' => 'trim' );
	
    /**
     * Overwrite parent showPage method in order to add the functionality of loading a json resource.
     */
    public function showPage() {
		$this->jsonloader->loadIndex();
		parent::showPage(); 
    }
	
    /**
     * @throws GeneralException
     *
     * $this->getParameters['res'] resource key index
     */
	public function getRequest() {
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
		$this->resource = $this->jsonloader->loadResource( $this->getParameters['res'] );
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Admin entity view';
		
		$info = new BaseInfo;
		$info->setTitle( 'Entity name: '.$this->resource->name );
		$info->addParagraph( 'Database table name: '.$this->resource->entity->tablename, '' );

		$tableExists = $this->queryExecuter->executeTableExists( $this->queryBuilder->tableExists($this->resource->entity->tablename) );
			
		$info->addParagraph( 'Table exists: '.( $tableExists ? 
			'true  '.Button::get($this->router->make_url( Router::ROUTE_ADMIN_ENTITY_DROP_TABLE, 'res='.$this->resource->name ), 'Drop', Button::COLOR_GRAY.' '.Button::SMALL ) : 
			'false  '.Button::get($this->router->make_url( Router::ROUTE_ADMIN_ENTITY_CREATE_TABLE, 'res='.$this->resource->name ), 'Create', Button::COLOR_GRAY.' '.Button::SMALL )
		), '' );

        $resourcesTable = new StaticTable;
        $resourcesTable->setTitle("Called from resources");
        $resourcesTable->addTHead();
        $resourcesTable->addRow();
        $resourcesTable->addHeadLineColumn('Name');
        $resourcesTable->addHeadLineColumn('Type');
        $resourcesTable->addHeadLineColumn('SQL');
        $resourcesTable->closeRow();
        $resourcesTable->closeTHead();
        $resourcesTable->addTBody();
        foreach ( $this->jsonloader->getResourcesIndex() as $reskey => $resvalue ) {
            $tmpres = $this->jsonloader->loadResource( $reskey );
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
            if (isset($tmpres->get->logics)) {
                foreach ( $tmpres->get->logics as $logic ) {
                    if ( isset($logic->sql) AND strpos($logic->sql, $this->resource->entity->tablename) !== false )
                    {
                        $resourcesTable->addRow();
                        $resourcesTable->addColumn($reskey);
                        $resourcesTable->addColumn($tmpres->metadata->type);
                        $resourcesTable->addColumn($logic->sql);
                        $resourcesTable->closeRow();
                    }
                }
            }
            if (isset($tmpres->post->logics)) {
                foreach ( $tmpres->post->logics as $logic ) {
                    if ( isset($logic->sql) AND strpos($logic->sql, $this->resource->entity->tablename) !== false )
                    {
                        $resourcesTable->addRow();
                        $resourcesTable->addColumn($reskey);
                        $resourcesTable->addColumn($tmpres->metadata->type);
                        $resourcesTable->addColumn($logic->sql);
                        $resourcesTable->closeRow();
                    }
                }
            }
        }
        $resourcesTable->closeTBody();
		
		$this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST ) );
		$this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->router ) );
		$this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array( $resourcesTable );
        $this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
	}

}
