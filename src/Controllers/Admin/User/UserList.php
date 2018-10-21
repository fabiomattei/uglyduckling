<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 21/10/2018
 * Time: 10:38
 */

namespace Firststep\Controllers\Admin\User;

use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Blocks\Button;
use Firststep\Common\Router\Router;

/**
 * This class gives a list of all entities loaded in to the system
 */
class UserList extends Controller {

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Users list';

        $table = new StaticTable;
        $table->setTitle('Users list');

        $table->addTHead();
        $table->addRow();
        $table->addHeadLineColumn('Name');
        $table->addHeadLineColumn('Surname');
        $table->addHeadLineColumn('Main Group');
        $table->addHeadLineColumn(''); // adding one more for actions
        $table->closeRow();
        $table->closeTHead();

        $table->addTBody();
        foreach ( $this->jsonloader->getResourcesIndex() as $res ) {
            if ( $res->type === 'entity' ) {
                $table->addRow();
                $table->addColumn($res->name);
                $table->addColumn($res->surname);
                $table->addColumn($res->surname);
                $table->addUnfilteredColumn( Button::get($this->router->make_url( Router::ROUTE_ADMIN_ENTITY_VIEW, 'res='.$res->name ), 'View', Button::COLOR_GRAY.' '.Button::SMALL ) );
                $table->closeRow();
            }
        }
        $table->closeTBody();

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->router ) );
        $this->centralcontainer = array( $table );
    }

}
