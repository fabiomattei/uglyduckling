<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 01/11/18
 * Time: 6.07
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\Search;

use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLInfo;
use Fabiom\UglyDuckling\Common\Router\Router;

class AdminSearchView extends Controller {

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

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Admin search view';

        $info = new BaseHTMLInfo;
        $info->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $info->setTitle( 'Search name: '.$this->resource->name );
        $info->addParagraph('Allowed groups: '.implode(', ',$this->resource->allowedgroups), '6');
        $info->addParagraph('Post SQL Query: '.$this->resource->post->query->sql, '6');

        $fieldsFormTable = new StaticTable;
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

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_SEARCH_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_SEARCH_LIST, $this->routerContainer ) );
        $this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array( $fieldsFormTable );
        $this->thirdcentralcontainer = array( $fieldsTable );

        $this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
    }

}
