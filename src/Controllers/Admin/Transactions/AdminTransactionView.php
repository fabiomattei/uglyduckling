<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 01/11/18
 * Time: 5.14
 */

namespace Firststep\Controllers\Admin\Transactions;

use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Blocks\BaseInfo;
use Firststep\Common\Router\Router;

class AdminTransactionView extends Controller {

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
        $info->setTitle( 'Logic name: '.$this->resource->name );
        $info->addParagraph('Allowed groups: '.implode(', ',$this->resource->allowedgroups), '6');

        $fieldsTable = new StaticTable;
        $fieldsTable->setTitle("GET Parameters");
        $fieldsTable->addTHead();
        $fieldsTable->addRow();
        $fieldsTable->addHeadLineColumn('Type');
        $fieldsTable->addHeadLineColumn('Name');
        $fieldsTable->addHeadLineColumn('Validation');
        $fieldsTable->closeRow();
        $fieldsTable->closeTHead();
        $fieldsTable->addTBody();
        foreach ( $this->resource->get->request->parameters as $par ) {
            $fieldsTable->addRow();
            $fieldsTable->addColumn($par->type ?? 'Undefined');
            $fieldsTable->addColumn($par->name ?? 'Undefined');
            $fieldsTable->addColumn($par->validation ?? 'Undefined');
            $fieldsTable->closeRow();
        }
        $fieldsTable->closeTBody();

        $actionsTable = new StaticTable;
        $actionsTable->setTitle("Logics");
        $actionsTable->addTHead();
        $actionsTable->addRow();
        $actionsTable->addHeadLineColumn('SQL');
        $actionsTable->closeRow();
        $actionsTable->closeTHead();
        $actionsTable->addTBody();
        print_r($this->resource->get->transactions);
        foreach ( $this->resource->get->transactions as $transaction ) {
            $actionsTable->addRow();
            $actionsTable->addColumn($transaction->sql ?? 'Undefined');
            $actionsTable->closeRow();
        }
        $actionsTable->closeTBody();

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_FORM_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_FORM_LIST, $this->router ) );
        $this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array( $fieldsTable );
        $this->thirdcentralcontainer = array( $actionsTable );

        $this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
    }

}
