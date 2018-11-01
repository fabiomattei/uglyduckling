<?php

namespace Firststep\Templates\Blocks\Sidebars;

use Firststep\Common\Blocks\BaseBlock;
use Firststep\Common\Router\Router;

class AdminSidebar extends BaseBlock {
	
	function __construct( string $appname, string $active = 'home', $router ) {
		$this->appname = $appname;
		$this->active = $active;
		$this->router = $router;
	}

	// icons list here: https://feathericons.com/

    function show(): string {
		$out = '<div class="sidebar-sticky">
                <ul class="nav flex-column">
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_DASHBOARD ? 'active' : '' ).'" href="'.$this->router->make_url( Router::ROUTE_ADMIN_DASHBOARD ).'">
                      <span data-feather="home"></span>
                      Dashboard <span class="sr-only">(current)</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_ENTITY_LIST ? 'active' : '' ).'" href="'.$this->router->make_url( Router::ROUTE_ADMIN_ENTITY_LIST ).'">
                      <span data-feather="file"></span>
                      Entities
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_DOCUMENT_LIST ? 'active' : '' ).'" href="'.$this->router->make_url( Router::ROUTE_ADMIN_DOCUMENT_LIST ).'">
                      <span data-feather="file"></span>
                      Documents
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_TABLE_LIST ? 'active' : '' ).'" href="'.$this->router->make_url( Router::ROUTE_ADMIN_TABLE_LIST ).'">
                      <span data-feather="layers"></span>
                      Tables
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_REPORT_LIST ? 'active' : '' ).'" href="'.$this->router->make_url( Router::ROUTE_ADMIN_REPORT_LIST ).'">
                      <span data-feather="bar-chart-2"></span>
                      Exports
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_REPORT_LIST ? 'active' : '' ).'" href="'.$this->router->make_url( Router::ROUTE_ADMIN_REPORT_LIST ).'">
                      <span data-feather="search"></span>
                      Searches
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" '.( $this->active === Router::ROUTE_ADMIN_FORM_LIST ? 'active' : '' ).'" href="'.$this->router->make_url( Router::ROUTE_ADMIN_FORM_LIST ).'">
                      <span data-feather="edit"></span>
                      Forms
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" '.( $this->active === Router::ROUTE_ADMIN_LOGIC_LIST ? 'active' : '' ).'" href="'.$this->router->make_url( Router::ROUTE_ADMIN_LOGIC_LIST ).'">
                      <span data-feather="cpu"></span>
                      Logics
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">
                      <span data-feather="target"></span>
                      Dashboards
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">
                      <span data-feather="eye"></span>
                      Infos
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" '.( $this->active === Router::ROUTE_ADMIN_GROUP_LIST ? 'active' : '' ).'" href="'.$this->router->make_url( Router::ROUTE_ADMIN_GROUP_LIST ).'">
                      <span data-feather="users"></span>
                      Groups
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link '.( $this->active === Router::ROUTE_ADMIN_USER_LIST ? 'active' : '' ).'" href="'.$this->router->make_url( Router::ROUTE_ADMIN_USER_LIST ).'">
                      <span data-feather="user"></span>
                      Users
                    </a>
                  </li>
                </ul>
                </div>';
        return $out; 
    }
	
}
