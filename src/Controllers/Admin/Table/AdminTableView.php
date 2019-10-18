<?php

/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 29/10/18
 * Time: 15.57
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\Table;

use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Common\Json\Checkers\Form\FormV1JsonChecker;
use Fabiom\UglyDuckling\Common\Json\Parameters\BasicParameterGetter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLInfo;
use Fabiom\UglyDuckling\Common\Router\Router;
use Fabiom\UglyDuckling\Common\Json\Checkers\BasicJsonChecker;

class AdminTableView extends Controller {

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
        $this->resource = $this->jsonloader->loadResource( $this->getParameters['res'] );

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Admin entity view';

        $info = new BaseHTMLInfo;
		$info->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $info->setTitle( 'Table name: '.$this->resource->name );
        $info->addParagraph('Allowed groups: '.implode(', ',$this->resource->allowedgroups), '6');
        $info->addParagraph('SQL Query: '.$this->resource->get->query->sql, '6');

        $fieldsTable = new StaticTable;
		$fieldsTable->setHtmlTemplateLoader( $this->htmlTemplateLoader );
		
        $fieldsTable->setTitle("Fields");
        $fieldsTable->addTHead();
        $fieldsTable->addRow();
        $fieldsTable->addHeadLineColumn('Headline');
        $fieldsTable->addHeadLineColumn('Sql field');
        $fieldsTable->closeRow();
        $fieldsTable->closeTHead();
        $fieldsTable->addTBody();
        foreach ( $this->resource->get->table->fields as $field ) {
            $fieldsTable->addRow();
            $fieldsTable->addColumn($field->headline);
            $fieldsTable->addColumn($field->sqlfield);
            $fieldsTable->closeRow();
        }
        $fieldsTable->closeTBody();

        $actionsTable = new StaticTable;
		$actionsTable->setHtmlTemplateLoader( $this->htmlTemplateLoader );
		
        $actionsTable->setTitle("Actions");
        $actionsTable->addTHead();
        $actionsTable->addRow();
        $actionsTable->addHeadLineColumn('Label');
        $actionsTable->addHeadLineColumn('Action');
        $actionsTable->addHeadLineColumn('Resource');
        $actionsTable->closeRow();
        $actionsTable->closeTHead();
        $actionsTable->addTBody();
        foreach ( $this->resource->get->table->actions as $action ) {
            $actionsTable->addRow();
            $actionsTable->addColumn($action->label ?? '');
            $actionsTable->addColumn($action->action ?? '');
            $actionsTable->addColumn($action->resource ?? '');
            $actionsTable->closeRow();
        }
        $actionsTable->closeTBody();

        $resourcesTable = new StaticTable;
		$resourcesTable->setHtmlTemplateLoader( $this->htmlTemplateLoader );
		
        $resourcesTable->setTitle("Actions pointing to this resource");
        $resourcesTable->addTHead();
        $resourcesTable->addRow();
        $resourcesTable->addHeadLineColumn('Name');
        $resourcesTable->addHeadLineColumn('Satus');
        $resourcesTable->closeRow();
        $resourcesTable->closeTHead();
        $resourcesTable->addTBody();
        foreach ( $this->jsonloader->getResourcesIndex() as $reskey => $resvalue ) {
            $tmpres = $this->jsonloader->loadResource( $reskey );
            $checker = BasicJsonChecker::basicJsonCheckerFactory( $tmpres );
			if ( $checker->isActionPresent( $this->resource->name ) ) {
	            $resourcesTable->addRow();
	            $resourcesTable->addColumn( $reskey );
                $parametersGetter = BasicParameterGetter::basicParameterCheckerFactory( $this->resource, $this->jsonloader );
	            $resourcesTable->addColumn( $checker->isActionPresentAndWellStructured( $this->resource->name, $parametersGetter->getGetParameters() ) ? 'Ok' : $checker->getErrorsString() );
	            $resourcesTable->closeRow();
			}
            
        }
        $resourcesTable->closeTBody();

        $resourceGeneralChecks = new StaticTable;
        $resourceGeneralChecks->setHtmlTemplateLoader( $this->htmlTemplateLoader );

        $resourceGeneralChecks->setTitle("General checks");
        $resourceGeneralChecks->addTHead();
        $resourceGeneralChecks->addRow();
        $resourceGeneralChecks->addHeadLineColumn('Name');
        $resourceGeneralChecks->addHeadLineColumn('Staus');
        $resourceGeneralChecks->closeRow();
        $resourceGeneralChecks->closeTHead();
        $resourceGeneralChecks->addTBody();
        $tmpres = $this->jsonloader->loadResource( $reskey );
        $checker = BasicJsonChecker::basicJsonCheckerFactory( $tmpres );
        $resourceGeneralChecks->addRow();
        $resourceGeneralChecks->addColumn( 'Resource well structured' );
        $resourceGeneralChecks->addColumn( $checker->isResourceBlockWellStructured() ? 'Ok' : $checker->getErrorsString() );
        $resourceGeneralChecks->closeRow();
        $resourceGeneralChecks->closeTBody();

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->routerContainer ) );
        $this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array( $fieldsTable );
        $this->thirdcentralcontainer = array( $actionsTable, $resourcesTable, $resourceGeneralChecks );

        $this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
    }

}

