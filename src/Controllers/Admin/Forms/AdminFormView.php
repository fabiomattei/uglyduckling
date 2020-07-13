<?php

/**
 * Created by Fabio Mattei
 * Date: 31/10/18
 * Time: 16.02
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\Forms;

use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Common\Json\Parameters\BasicParameterGetter;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLInfo;
use Fabiom\UglyDuckling\Common\Json\Checkers\BasicJsonChecker;

class AdminFormView extends Controller {

    public $get_validation_rules = array( 'res' => 'required|max_len,50' );
    public $get_filter_rules     = array( 'res' => 'trim' );

    /**
     * Overwrite parent showPage method in order to add the functionality of loading a json resource.
     */
    public function showPage() {
        $this->applicationBuilder->getJsonloader()->loadIndex();
        parent::showPage();
    }

    /**
     * @throws GeneralException
     *
     * $this->getParameters['res'] resource key index
     */
    public function getRequest() {
        $this->resource = $this->applicationBuilder->getJsonloader()->loadResource( $this->getParameters['res'] );

        $this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Admin form view';

        $info = new BaseHTMLInfo;
		$info->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $info->setTitle( 'Form name: '.$this->resource->name );
        $info->addParagraph('Allowed groups: '.implode(', ',$this->resource->allowedgroups), '6');
        $info->addParagraph('SQL Query: '.$this->resource->get->query->sql, '6');

        $fieldsTable = new StaticTable;
		$fieldsTable->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $fieldsTable->setTitle("Fields");
        $fieldsTable->addTHead();
        $fieldsTable->addRow();
        $fieldsTable->addHeadLineColumn('Type');
        $fieldsTable->addHeadLineColumn('Name');
        $fieldsTable->addHeadLineColumn('Label');
        $fieldsTable->addHeadLineColumn('Placeholder');
        $fieldsTable->addHeadLineColumn('SQL Field');
        $fieldsTable->addHeadLineColumn('Widht');
        $fieldsTable->addHeadLineColumn('Row');
        $fieldsTable->closeRow();
        $fieldsTable->closeTHead();
        $fieldsTable->addTBody();
        foreach ( $this->resource->get->form->fields as $field ) {
            $fieldsTable->addRow();
            $fieldsTable->addColumn($field->type ?? 'Undefined');
            $fieldsTable->addColumn($field->name ?? 'Undefined');
            $fieldsTable->addColumn($field->label ?? 'Undefined');
            $fieldsTable->addColumn($field->placeholder ?? 'Undefined');
            $fieldsTable->addColumn($field->sqlfield ?? 'Undefined');
            $fieldsTable->addColumn($field->width ?? 'Undefined');
            $fieldsTable->addColumn($field->row ?? 'Undefined');
            $fieldsTable->closeRow();
        }
        $fieldsTable->closeTBody();

        $actionsTable = new StaticTable;
		$actionsTable->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $actionsTable->setTitle("Logics");
        $actionsTable->addTHead();
        $actionsTable->addRow();
        $actionsTable->addHeadLineColumn('SQL');
        $actionsTable->closeRow();
        $actionsTable->closeTHead();
        $actionsTable->addTBody();
        foreach ( $this->resource->post->transactions as $transaction ) {
            $actionsTable->addRow();
            $actionsTable->addColumn($transaction->sql ?? 'Undefined');
            $actionsTable->closeRow();
        }
        $actionsTable->closeTBody();
		
        $resourcesTable = new StaticTable;
		$resourcesTable->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
		
        $resourcesTable->setTitle("Actions pointing to this resource");
        $resourcesTable->addTHead();
        $resourcesTable->addRow();
        $resourcesTable->addHeadLineColumn('Name');
        $resourcesTable->addHeadLineColumn('Satus');
        $resourcesTable->closeRow();
        $resourcesTable->closeTHead();
        $resourcesTable->addTBody();
        foreach ( $this->applicationBuilder->getJsonloader()->getResourcesIndex() as $reskey => $resvalue ) {
            $tmpres = $this->applicationBuilder->getJsonloader()->loadResource( $reskey );
            $checker = BasicJsonChecker::basicJsonCheckerFactory( $tmpres );
			if ( $checker->isActionPresent( $this->resource->name ) ) {
	            $resourcesTable->addRow();
	            $resourcesTable->addColumn( $reskey );
                $parametersGetter = BasicParameterGetter::basicParameterCheckerFactory( $this->resource, $this->applicationBuilder->getJsonloader() );
	            $resourcesTable->addColumn( $checker->isActionPresentAndWellStructured( $this->resource->name, $parametersGetter->getGetParameters() ) ? 'Ok' : $checker->getErrorsString() );
	            $resourcesTable->closeRow();
			}
            
        }
        $resourcesTable->closeTBody();

        $resourceGeneralChecks = new StaticTable;
        $resourceGeneralChecks->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );

        $resourceGeneralChecks->setTitle("General checks");
        $resourceGeneralChecks->addTHead();
        $resourceGeneralChecks->addRow();
        $resourceGeneralChecks->addHeadLineColumn('Name');
        $resourceGeneralChecks->addHeadLineColumn('Staus');
        $resourceGeneralChecks->closeRow();
        $resourceGeneralChecks->closeTHead();
        $resourceGeneralChecks->addTBody();
        $tmpres = $this->applicationBuilder->getJsonloader()->loadResource( $reskey );
        $checker = BasicJsonChecker::basicJsonCheckerFactory( $tmpres );
        $resourceGeneralChecks->addRow();
        $resourceGeneralChecks->addColumn( 'Resource well structured' );
        $resourceGeneralChecks->addColumn( $checker->isResourceBlockWellStructured() ? 'Ok' : $checker->getErrorsString() );
        $resourceGeneralChecks->closeRow();
        $resourceGeneralChecks->closeTBody();

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_FORM_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_FORM_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array( $fieldsTable );
        $this->thirdcentralcontainer = array( $actionsTable, $resourcesTable, $resourceGeneralChecks );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
