<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 01/11/18
 * Time: 5.14
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\Transactions;

use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLInfo;
use Fabiom\UglyDuckling\Common\Router\ResourceRouter;

class AdminTransactionView extends AdminController {

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
        $info->setTitle( 'Logic name: '.$this->resource->name );
        $info->addParagraph('Allowed groups: '.implode(', ',$this->resource->allowedgroups), '6');

        $fieldsTable = new StaticTable;
        $fieldsTable->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
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
        $actionsTable->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $actionsTable->setTitle("Logics");
        $actionsTable->addTHead();
        $actionsTable->addRow();
        $actionsTable->addHeadLineColumn('SQL');
        $actionsTable->closeRow();
        $actionsTable->closeTHead();
        $actionsTable->addTBody();
        foreach ( $this->resource->get->transactions as $transaction ) {
            $actionsTable->addRow();
            $actionsTable->addColumn($transaction->sql ?? 'Undefined');
            $actionsTable->closeRow();
        }
        $actionsTable->closeTBody();

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_FORM_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_FORM_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array( $fieldsTable );
        $this->thirdcentralcontainer = array( $actionsTable );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
