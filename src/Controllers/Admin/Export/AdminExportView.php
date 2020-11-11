<?php
/**
 * Created by Fabio Mattei
 * 
 * Date: 01/11/18
 * Time: 5.53
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\Export;

use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLInfo;

class AdminExportView extends AdminController {

    public $get_validation_rules = array( 'res' => 'required|max_len,50' );
    public $get_filter_rules     = array( 'res' => 'trim' );

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
        $info->setTitle( 'Export name: '.$this->resource->name );
        $info->addParagraph('Allowed groups: '.implode(', ',$this->resource->allowedgroups), '6');
        $info->addParagraph('Post SQL Query: '.$this->resource->post->query->sql, '6');

        $fieldsFormTable = new StaticTable;
        $fieldsFormTable->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $fieldsFormTable->setTitle("Form fields");
        $fieldsFormTable->addTHead();
        $fieldsFormTable->addRow();
        $fieldsFormTable->addHeadLineColumn('Type');
        $fieldsFormTable->addHeadLineColumn('Name');
        $fieldsFormTable->addHeadLineColumn('Label');
        $fieldsFormTable->addHeadLineColumn('Placeholder');
        $fieldsFormTable->addHeadLineColumn('SQL Field');
        $fieldsFormTable->addHeadLineColumn('Widht');
        $fieldsFormTable->addHeadLineColumn('Row');
        $fieldsFormTable->closeRow();
        $fieldsFormTable->closeTHead();
        $fieldsFormTable->addTBody();
        foreach ( $this->resource->get->form->fields as $field ) {
            $fieldsFormTable->addRow();
            $fieldsFormTable->addColumn($field->type ?? 'Undefined');
            $fieldsFormTable->addColumn($field->name ?? 'Undefined');
            $fieldsFormTable->addColumn($field->label ?? 'Undefined');
            $fieldsFormTable->addColumn($field->placeholder ?? 'Undefined');
            $fieldsFormTable->addColumn($field->sqlfield ?? 'Undefined');
            $fieldsFormTable->addColumn($field->width ?? 'Undefined');
            $fieldsFormTable->addColumn($field->row ?? 'Undefined');
            $fieldsFormTable->closeRow();
        }
        $fieldsFormTable->closeTBody();

        $fieldsTable = new StaticTable;
        $fieldsTable->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $fieldsTable->setTitle("Fields");
        $fieldsTable->addTHead();
        $fieldsTable->addRow();
        $fieldsTable->addHeadLineColumn('Headline');
        $fieldsTable->addHeadLineColumn('Sql field');
        $fieldsTable->closeRow();
        $fieldsTable->closeTHead();
        $fieldsTable->addTBody();
        foreach ( $this->resource->post->table->fields as $field ) {
            $fieldsTable->addRow();
            $fieldsTable->addColumn($field->headline);
            $fieldsTable->addColumn($field->sqlfield);
            $fieldsTable->closeRow();
        }
        $fieldsTable->closeTBody();

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_FORM_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_FORM_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array( $fieldsFormTable );
        $this->thirdcentralcontainer = array( $fieldsTable );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
