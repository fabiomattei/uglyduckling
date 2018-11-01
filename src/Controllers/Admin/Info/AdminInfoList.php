<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 01/11/18
 * Time: 5.30
 */

namespace Firststep\Controllers\Admin\Info;

use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Blocks\Button;
use Firststep\Common\Router\Router;

class AdminInfoList extends Controller {

    /**
     * Overwrite parent showPage method in order to add the functionality of loading a json resource.
     */
    public function showPage() {
        $this->jsonloader->loadIndex();
        parent::showPage();
    }

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Admin Forms list';

        $table = new StaticTable;
        $table->setTitle('Forms list');

        $table->addTHead();
        $table->addRow();
        $table->addHeadLineColumn('Name');
        $table->addHeadLineColumn('Type');
        $table->addHeadLineColumn(''); // adding one more for actions
        $table->closeRow();
        $table->closeTHead();

        $table->addTBody();
        foreach ( $this->jsonloader->getResourcesIndex() as $res ) {
            if ( $res->type === 'info' ) {
                $table->addRow();
                $table->addColumn($res->name);
                $table->addColumn($res->type);
                $table->addUnfilteredColumn( Button::get($this->router->make_url( Router::ROUTE_ADMIN_INFO_VIEW, 'res='.$res->name ), 'View', Button::COLOR_GRAY.' '.Button::SMALL ) );
                $table->closeRow();
            }
        }
        $table->closeTBody();

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_INFO_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_INFO_LIST, $this->router ) );
        $this->centralcontainer = array( $table );
    }

}
