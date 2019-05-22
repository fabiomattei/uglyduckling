<?php

namespace Firststep\Controllers\Admin\Document;

use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Blocks\BaseInfo;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Json\JsonCreateQueryParser;

/**
 * 
 */
class AdminDocumentCreateTable extends Controller {
	
	function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->jsonCreateQueryParser = new JsonCreateQueryParser;
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
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Admin document create';
		
		$info = new BaseInfo;
        $info->setHtmlTemplateLoader( $this->htmlTemplateLoader );
		$info->setTitle( 'Document name: '.$this->resource->name );
		$info->addParagraph( 'Table name: '.$this->resource->entity->tablename, '' );
		
		list( $create, $addPrimaryKey, $addAutoIncrement) = $this->jsonCreateQueryParser->parse( $this->resource );

		$this->queryExecuter->executeTableCreate( $create );
		$this->queryExecuter->executeTableCreate( $addPrimaryKey );
		$this->queryExecuter->executeTableCreate( $addAutoIncrement );
			
		$info->addParagraph( 'Table created! ', '' );
		
		$this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_DOCUMENT_LIST ) );
		$this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_DOCUMENT_LIST, $this->router ) );
		$this->centralcontainer = array( $info );

        $this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
	}

}
