<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 01/11/18
 * Time: 5.53
 */

namespace Firststep\Controllers\Admin\Export;

use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Blocks\BaseInfo;
use Firststep\Common\Router\Router;

class AdminExportView extends Controller {

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
        $info->setTitle( 'Export name: '.$this->resource->name );
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

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_FORM_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_FORM_LIST, $this->router ) );
        $this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array( $fieldsFormTable );
        $this->thirdcentralcontainer = array( $fieldsTable );
    }

}
