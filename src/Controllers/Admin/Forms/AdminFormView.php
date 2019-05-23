<?php

/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 31/10/18
 * Time: 16.02
 */

namespace Firststep\Controllers\Admin\Forms;

use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Controllers\Controller;
use Firststep\Common\Json\Checkers\Form\FormV1JsonChecker;
use Firststep\Common\Json\Parameters\BasicParameterGetter;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Blocks\BaseInfo;
use Firststep\Common\Router\Router;
use Firststep\Common\Json\Checkers\BasicJsonChecker;

class AdminFormView extends Controller {

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

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Admin form view';

        $info = new BaseInfo;
		$info->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $info->setTitle( 'Form name: '.$this->resource->name );
        $info->addParagraph('Allowed groups: '.implode(', ',$this->resource->allowedgroups), '6');
        $info->addParagraph('SQL Query: '.$this->resource->get->query->sql, '6');

        $fieldsTable = new StaticTable;
		$fieldsTable->setHtmlTemplateLoader( $this->htmlTemplateLoader );
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
		$actionsTable->setHtmlTemplateLoader( $this->htmlTemplateLoader );
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
        $resourceGeneralChecks->addHeadLineColumn('Satus');
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

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_FORM_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_FORM_LIST, $this->router ) );
        $this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array( $fieldsTable );
        $this->thirdcentralcontainer = array( $actionsTable, $resourcesTable, $resourceGeneralChecks );

        $this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
    }

}
