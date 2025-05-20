<?php
/**
 * Created by Fabio Mattei
 * 
 * Date: 01/11/18
 * Time: 5.30
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\Info;

use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLInfo;

class AdminInfoView extends AdminController {

    public $get_validation_rules = array( 'res' => 'required|max_len,50' );
    public $get_filter_rules     = array( 'res' => 'trim' );
    public $resource;

    /**
     * @throws GeneralException
     *
     * $this->getParameters['res'] resource key index
     */
    public function getRequest() {
        $this->resource = $this->applicationBuilder->getJsonloader()->loadResource( $this->getParameters['res'] );

        $this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Admin info view';

        $info = new BaseHTMLInfo;
        $info->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $info->setTitle( 'Info name: '.$this->resource->name );
        $info->addParagraph('Allowed groups: '.implode(', ',$this->resource->allowedgroups), '6');
        $info->addParagraph('SQL Query: '.$this->resource->get->query->sql, '6');

        $parametersTable = new StaticTable;
        $parametersTable->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $parametersTable->setTitle("GET Parameters");
        $parametersTable->addTHead();
        $parametersTable->addRow();
        $parametersTable->addHeadLineColumn('Type');
        $parametersTable->addHeadLineColumn('Name');
        $parametersTable->addHeadLineColumn('Validation');
        $parametersTable->closeRow();
        $parametersTable->closeTHead();
        $parametersTable->addTBody();
        foreach ( $this->resource->get->request->parameters as $par ) {
            $parametersTable->addRow();
            $parametersTable->addColumn($par->type ?? 'Undefined');
            $parametersTable->addColumn($par->name ?? 'Undefined');
            $parametersTable->addColumn($par->validation ?? 'Undefined');
            $parametersTable->closeRow();
        }
        $parametersTable->closeTBody();

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
        foreach ( $this->resource->get->info->fields as $field ) {
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

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_FORM_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_FORM_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array( $parametersTable );
        $this->thirdcentralcontainer = array( $fieldsTable );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
