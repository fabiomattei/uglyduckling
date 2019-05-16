<?php

/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 29/10/18
 * Time: 15.57
 */

namespace Firststep\Controllers\Admin\Table;

use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Blocks\BaseInfo;
use Firststep\Common\Router\Router;
use Firststep\Common\Json\Checkers\BasicJsonChecker;

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

        $info = new BaseInfo;
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

            $resourcesTable->addRow();
            $resourcesTable->addColumn($reskey);
            $resourcesTable->addColumn($checker->isActionPresentAndWellStructured( $this->resource->metadata->name, $this->resource->get->request->parameters ) ? 'Ok' : $checker->getErrorsString() );
            $resourcesTable->closeRow();
        }
        $resourcesTable->closeTBody();

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->router ) );
        $this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array( $fieldsTable );
        $this->thirdcentralcontainer = array( $actionsTable, $resourcesTable );

        $this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
    }

}

