<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 01/11/18
 * Time: 5.30
 */

namespace Firststep\Controllers\Admin\Info;

use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Blocks\BaseInfo;
use Firststep\Common\Router\Router;

class AdminInfoView extends Controller {

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

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Admin info view';

        $info = new BaseInfo;
        $info->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $info->setTitle( 'Info name: '.$this->resource->name );
        $info->addParagraph('Allowed groups: '.implode(', ',$this->resource->allowedgroups), '6');
        $info->addParagraph('SQL Query: '.$this->resource->get->query->sql, '6');

        $parametersTable = new StaticTable;
        $parametersTable->setHtmlTemplateLoader( $this->htmlTemplateLoader );
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

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_FORM_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_FORM_LIST, $this->router ) );
        $this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array( $parametersTable );
        $this->thirdcentralcontainer = array( $fieldsTable );

        $this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
    }

}
